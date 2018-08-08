<?php

namespace Application\Repository;


use Application\Entity\Shop\ShopItemParameter;
use Doctrine\ORM\EntityRepository;

class ShopItemParameterRepository extends EntityRepository
{
    public function findAllShopItemParameters($shopItem)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(ShopItemParameter::class, 'u')
            ->AndWhere('u.shopItem = :shId')
            ->setParameter('shId', $shopItem)
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}