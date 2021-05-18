<?php

declare(strict_types=1);

namespace App\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Core\Model\ChannelInterface;

final class ProductRepository extends BaseProductRepository
{
    private const DEFAULT_LIMIT = 12;

    public function findAllByBestsellers(ChannelInterface $channel, string $locale, int $limit = self::DEFAULT_LIMIT): array
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = true')
            ->setParameter('channel', $channel)
            ->setParameter('locale', $locale)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }
}
