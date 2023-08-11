<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Odiseo\SyliusVendorPlugin\Repository\ProductRepositoryTrait;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Core\Model\ChannelInterface;
use Odiseo\SyliusVendorPlugin\Repository\ProductRepositoryInterface;

final class ProductRepository extends BaseProductRepository implements
    ProductRepositoryInterface
{

    use ProductRepositoryTrait;

    private const DEFAULT_LIMIT = 12;

    private const CATEGORY_BESTSELLERS = 'bestsellers';
    private const CATEGORY_LATEST = 'latest';

    public function findAllByBestsellers(
        ChannelInterface $channel,
        string $locale,
        int $limit = self::DEFAULT_LIMIT
    ): array {
        return $this->getByCategoryQueryBuilder($channel, $locale, self::CATEGORY_BESTSELLERS)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAllByRefund(
        ChannelInterface $channel,
        string $locale,
        int $limit = self::DEFAULT_LIMIT
    ): Pagerfanta
    {
        return $this->getPaginator($this->getRefundQueryBuilder($channel, $locale)

            ->setMaxResults($limit)
            );
    }

    public function findLatestByChannel(
        ChannelInterface $channel,
        string $locale,
        int $limit = self::DEFAULT_LIMIT
    ): array {
        return $this->getByCategoryQueryBuilder($channel, $locale, self::CATEGORY_LATEST)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAllByBestsellersPaginator(
        ChannelInterface $channel,
        string $locale
    ): Pagerfanta {
        return $this->getPaginator(
            $this->getByCategoryQueryBuilder($channel, $locale, self::CATEGORY_BESTSELLERS)
        );
    }

    public function findAllByCategoryPaginator(
        ChannelInterface $channel,
        string $locale,
        string $code
    ): Pagerfanta {
        return $this->getPaginator(
            $this->getByCategoryQueryBuilder($channel, $locale, $code)
        );
    }

    public function findAllByCategory(
        ChannelInterface $channel,
        string $locale,
        string $code,
        int $count
    ): array {
        return $this->getByCategoryQueryBuilder($channel, $locale, $code)
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    public function findLatestByChannelPaginator(
        ChannelInterface $channel,
        string $locale
    ): Pagerfanta {
        return $this->getPaginator(
            $this->getByCategoryQueryBuilder($channel, $locale, self::CATEGORY_LATEST)
        );
    }

    private function getRefundQueryBuilder(
        ChannelInterface $channel,
        string $locale
    ) {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin(
                'o.translations',
                'translation',
                'WITH',
                'translation.locale = :locale'
            )
            ->join('o.refunds','refunds')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = true')
            ->andWhere('refunds.active = true')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setParameter('channel', $channel)
            ->setParameter('locale', $locale);
    }

    /**
     * @deprecated change to ByCategory
     */
    private function getBestsellersQueryBuilder(
        ChannelInterface $channel,
        string $locale
    ): QueryBuilder {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin(
                'o.translations',
                'translation',
                'WITH',
                'translation.locale = :locale'
            )
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = true')
            ->setParameter('channel', $channel)
            ->setParameter('locale', $locale);
    }

    /**
     * @deprecated change to ByCategory
     */
    private function getLatestQueryBuilder(
        ChannelInterface $channel,
        string $locale
    ) {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin(
                'o.translations',
                'translation',
                'WITH',
                'translation.locale = :locale'
            )
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = true')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setParameter('channel', $channel)
            ->setParameter('locale', $locale);
    }

    private function getByCategoryQueryBuilder(
        ChannelInterface $channel,
        string $locale,
        string $code
    ): QueryBuilder {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin(
                'o.translations',
                'translation',
                'WITH',
                'translation.locale = :locale'
            )
            ->innerJoin('o.channels', 'channel')
            ->andWhere('o.enabled = true')
            ->andWhere(':channel MEMBER OF o.channels')
            ->innerJoin('o.productTaxons', 'productTaxons')
            ->addOrderBy('productTaxons.position', 'asc')
            ->innerJoin('productTaxons.taxon', 'taxon')
            ->andWhere('taxon.code = :code')
            ->setParameter('code', $code)
            ->setParameter('channel', $channel)
            ->setParameter('locale', $locale);
    }

}


//final class ProductRepositoryl extends BaseProductRepository
//{
//    private const DEFAULT_LIMIT = 12;
//
//    public function findAllByBestsellers(
//        ChannelInterface $channel,
//        string $locale,
//        int $limit = self::DEFAULT_LIMIT
//    ): array {
//        return $this->getBestsellersQueryBuilder($channel, $locale)
//            ->setMaxResults($limit)
//            ->getQuery()
//            ->getResult();
//    }
//
//    public function findAllByBestsellersPaginator(
//        ChannelInterface $channel,
//        string $locale
//    ): Pagerfanta {
//        return $this->getPaginator($this->getBestsellersQueryBuilder($channel, $locale));
//    }
//
//    public function findAllByCategoryPaginator(ChannelInterface $channel, string $code): Pagerfanta
//    {
//        return $this->getPaginator($this->getByCategoryQueryBuilder($channel, $code));
//    }
//
//    public function findAllByCategory(ChannelInterface $channel, string $code, int $count): array
//    {
//        return $this->getByCategoryQueryBuilder($channel, $code)
//            ->setMaxResults($count)
//            ->getQuery()
//            ->getResult();
//    }
//
//    public function findLatestByChannelPaginator(ChannelInterface $channel, string $locale): Pagerfanta
//    {
//        return $this->getPaginator($this->createQueryBuilder('o')
//            ->addSelect('translation')
//            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
//            ->andWhere(':channel MEMBER OF o.channels')
//            ->andWhere('o.enabled = true')
//            ->addOrderBy('o.createdAt', 'DESC')
//            ->setParameter('channel', $channel)
//            ->setParameter('locale', $locale));
//    }
//
//    private function getBestsellersQueryBuilder(ChannelInterface $channel, string $locale): QueryBuilder
//    {
//        return $this->createQueryBuilder('o')
//            ->addSelect('translation')
//            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
//            ->andWhere(':channel MEMBER OF o.channels')
//            ->andWhere('o.enabled = true')
//            ->setParameter('channel', $channel)
//            ->setParameter('locale', $locale);
//    }
//
//    private function getByCategoryQueryBuilder(ChannelInterface $channel, string $code): QueryBuilder
//    {
//        return $this->createQueryBuilder('o')
//            ->innerJoin('o.channels', 'channel')
//            ->andWhere('o.enabled = true')
//            ->andWhere('channel = :channel')
//            ->innerJoin('o.productTaxons', 'productTaxons')
//            ->addOrderBy('productTaxons.position', 'asc')
//            ->innerJoin('productTaxons.taxon', 'taxon')
//            ->andWhere('taxon.code = :code')
//            ->setParameter('code', $code)
//            ->setParameter('channel', $channel);
//    }
//}
