<?php

namespace Application\Repository;


use Application\Entity\Album;
use Doctrine\ORM\EntityRepository;

class AlbumRepository extends EntityRepository
{
    public function findAllAlbums()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Album::class, 'u')
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}