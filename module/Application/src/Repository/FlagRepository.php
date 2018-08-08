<?php

namespace Application\Repository;


use Application\Entity\Flag;
use Doctrine\ORM\EntityRepository;

class FlagRepository extends EntityRepository
{
    public function findAllFlag()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Flag::class, 'u')
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}