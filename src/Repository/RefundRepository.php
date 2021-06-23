<?php

declare(strict_types=1);

namespace App\Repository;

use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class RefundRepository extends EntityRepository implements RepositoryInterface
{
    public function findForCustomer(CustomerInterface $customer)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $ordersQuery = $conn->prepare("SELECT r.id FROM sylius_refund_order r WHERE r.order_id IN (SELECT o.id FROM sylius_order o WHERE o.customer_id = :1)");
        $refundIds = $ordersQuery->executeQuery([':1' => $customer->getId()])->fetchFirstColumn();
        $queryBuilder = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.id = :refunds')
            ->setParameter('refunds', $refundIds)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
