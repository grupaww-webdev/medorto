<?php

declare(strict_types=1);

namespace App\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;

final class ProductRepository extends BaseProductRepository
{
    private const DEFAULT_LIMIT = 12;

    public function findAllByBestsellers(int $limit = self::DEFAULT_LIMIT): array
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }
}
