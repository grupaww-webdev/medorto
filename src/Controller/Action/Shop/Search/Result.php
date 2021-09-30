<?php

declare(strict_types=1);


namespace App\Controller\Action\Shop\Search;

use MonsieurBiz\SyliusSearchPlugin\Context\TaxonContextInterface;
use MonsieurBiz\SyliusSearchPlugin\Exception\MissingLocaleException;
use MonsieurBiz\SyliusSearchPlugin\Exception\NotSupportedTypeException;
use MonsieurBiz\SyliusSearchPlugin\Helper\RenderDocumentUrlHelper;
use MonsieurBiz\SyliusSearchPlugin\Model\Config\GridConfig;
use MonsieurBiz\SyliusSearchPlugin\Model\Document\Index\Search;
use MonsieurBiz\SyliusSearchPlugin\Model\Document\ResultSet;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class Result
{
    public const SORT_ASC = 'asc';
    public const SORT_DESC = 'desc';

    /** @var Environment */
    private $templatingEngine;
    /** @var Search */
    private $documentSearch;
    /** @var ChannelContextInterface */
    private $channelContext;
    /** @var CurrencyContextInterface */
    private $currencyContext;
    /** @var GridConfig */
    private $gridConfig;
    /** @var RenderDocumentUrlHelper */
    private $renderDocumentUrlHelper;
    /** @var ProductRepositoryInterface */
    private $productRepository;
    /** @var RouterInterface */
    private $router;

    public function __construct(
        Environment $templatingEngine,
        Search $documentSearch,
        ChannelContextInterface $channelContext,
        CurrencyContextInterface $currencyContext,
        GridConfig $gridConfig,
        RenderDocumentUrlHelper $renderDocumentUrlHelper,
        ProductRepositoryInterface $productRepository,
        RouterInterface $router
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->documentSearch = $documentSearch;
        $this->channelContext = $channelContext;
        $this->currencyContext = $currencyContext;
        $this->gridConfig = $gridConfig;
        $this->renderDocumentUrlHelper = $renderDocumentUrlHelper;
        $this->productRepository = $productRepository;
        $this->router = $router;
    }


    public function __invoke(Request $request): Response
    {
        // Init grid config depending on request
        $this->gridConfig->init(GridConfig::SEARCH_TYPE, $request);

        // Perform search
        $resultSet = $this->documentSearch->search($this->gridConfig);

        // Redirect to document if only one result and no filter applied
        $appliedFilters = $this->gridConfig->getAppliedFilters();
        $results = [];
        if (1 === $resultSet->getTotalHits() && empty($appliedFilters)) {
            /** @var \MonsieurBiz\SyliusSearchPlugin\Model\Document\Result $document */
            $document = current($resultSet->getResults());
            try {
                $urlParams = $this->renderDocumentUrlHelper->getUrlParams($document);

                return new RedirectResponse($this->router->generate($urlParams->getPath(), $urlParams->getParams()));
            } catch (NotSupportedTypeException $e) {
                // Return list of results if cannot redirect, so ignore Exception
            } catch (MissingLocaleException $e) {
                // Return list of results if locale is missing
            }
        }

        if (1 < $resultSet->getTotalHits()) {
            $results = $this->productRepository->findBy(['id' => array_map(function($a) {
                return $a->getId();
            }, $resultSet->getResults())]);
        }

        $currencyCode = $this->currencyContext->getCurrencyCode();
        $formatter = new \NumberFormatter($request->getLocale() . '@currency=' . $currencyCode, \NumberFormatter::CURRENCY);

        // Display result list
        return new Response($this->templatingEngine->render('@MonsieurBizSyliusSearchPlugin/Search/result.html.twig', [
            'query' => $this->gridConfig->getQuery(),
            'limits' => $this->gridConfig->getLimits(),
            'resultSet' => $resultSet,
            'channel' => $this->channelContext->getChannel(),
            'currencyCode' => $this->currencyContext->getCurrencyCode(),
            'moneySymbol' => $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL),
            'gridConfig' => $this->gridConfig,
            'results' => $results
        ]));
    }
}
