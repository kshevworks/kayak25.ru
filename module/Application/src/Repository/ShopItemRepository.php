<?php

namespace Application\Repository;


use Application\Entity\Shop\ShopItem;
use Doctrine\ORM\EntityRepository;

class ShopItemRepository extends EntityRepository
{
    public function findAllShopItems()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(ShopItem::class, 'u')
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}