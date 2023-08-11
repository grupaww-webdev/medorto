<?php

namespace App\Twig;

use App\Entity\Product\ProductTaxon;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PromotionExtension extends AbstractExtension
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isPromotion', [$this, 'isPromotion']),
        ];
    }

    public function isPromotion()
    {
        /** @var ProductTaxon $taxon */
        $taxon =  $this->entityManager->getRepository(ProductTaxon::class)->findBy(['taxon' => 27]);

        return count($taxon) > 0;
    }
}
