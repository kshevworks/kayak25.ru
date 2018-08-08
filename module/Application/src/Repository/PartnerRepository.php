<?php

namespace Application\Repository;


use Application\Entity\Partner;
use Doctrine\ORM\EntityRepository;

class PartnerRepository extends EntityRepository
{
    public function findAllPartners()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Partner::class, 'u')
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}