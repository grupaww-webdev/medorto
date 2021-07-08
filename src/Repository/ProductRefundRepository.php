<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product\ProductRefundInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class ProductRefundRepository extends EntityRepository implements ProductRefundRepositoryInterface
{
    public function findOneByIdAndProductId($id, $productId): ?ProductRefundInterface
    {
        return $this->createQueryBuilder('pr')
            ->andWhere('pr.product = :productId')
            ->andWhere('pr.id = :id')
            ->setParameter('productId', $productId)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function createQueryBuilderByProductId(int $productId): QueryBuilder
    {
        return $this->createQueryBuilder('pr')
            ->andWhere('pr.product = :productId')
            ->setParameter('productId', $productId)
            ;
    }
}
