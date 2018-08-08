<?php

namespace Application\Repository;


use Application\Entity\Counter;
use Doctrine\ORM\EntityRepository;

class CounterRepository extends EntityRepository
{
    public function findAllCounters()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Counter::class, 'u')
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}