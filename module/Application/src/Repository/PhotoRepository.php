<?php

namespace Application\Repository;


use Application\Entity\Photo;
use Doctrine\ORM\EntityRepository;

class PhotoRepository extends EntityRepository
{
    public function findAllPhotos()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Photo::class, 'u')
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }

    public function findPhotosByAlbum($album)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Photo::class, 'u')
            ->where('u.albumId = :alId')
            ->setParameter('alId', $album->getId())
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}