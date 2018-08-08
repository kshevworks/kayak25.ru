<?php

namespace Application\Repository;


use Application\Entity\Shop\ShopItemGist;
use Doctrine\ORM\EntityRepository;

class ShopItemGistRepository extends EntityRepository
{
    public function findAllShopItemGists($shopItem)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(ShopItemGist::class, 'u')
            ->andWhere('u.shopItem = :shId')
            ->setParameter('shId', $shopItem)
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}