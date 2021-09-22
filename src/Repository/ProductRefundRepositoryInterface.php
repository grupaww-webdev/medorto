<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product\ProductRefundInterface;
use Doctrine\ORM\QueryBuilder;

interface ProductRefundRepositoryInterface
{
    public function findOneByIdAndProductId($id, $productId): ?ProductRefundInterface;
    public function createQueryBuilderByProductId(int $productId): QueryBuilder;
}
