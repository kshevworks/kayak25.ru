<?php

namespace Application\Repository;


use Application\Entity\Shop\ShopOrder;
use Doctrine\ORM\EntityRepository;

class ShopOrderRepository extends EntityRepository
{
    public function findAllShopOrders()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(ShopOrder::class, 'u')
            ->orderBy('u.publishTime');

        return $queryBuilder->getQuery();
    }
}