<?php

namespace Application\Repository;


use Application\Entity\Commander;
use Doctrine\ORM\EntityRepository;

class CommanderRepository extends EntityRepository
{
    public function findAllCommanders()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Commander::class, 'u')
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}