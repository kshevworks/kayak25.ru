<?php

namespace Application\Service;

use Application\Entity\Album;
use Doctrine\ORM\EntityManager;

class AlbumManager
{
    /** @var EntityManager */
    private $entityManager;

    private $viewRenderer;

    private $config;

    private $UPLOAD_DIR = '/img/uploads/gallery/';

    public function __construct($entityManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * @var array $data
     * @return Album
     * @throws
     */
    public function addAlbum($data)
    {
        $album = new Album();

        $album->setName($data['name']);
        $album->setDescription($data['description']);
        $album->setDate($data['name']);
        $album->setDate(\DateTime::createFromFormat('Y-m-d', date('Y-m-d')));

        $this->entityManager->persist($album);

        $this->entityManager->flush();
        mkdir('public/' . $this->UPLOAD_DIR . $album->getName(), 777);
        return $album;
    }

    /**
     * @var Album $album
     * @var array $data
     * @return Album
     * @throws
     */
    public function updateAlbum($album, $data)
    {
        $oldAlbumName = $album->getName();
        $album->setName($data['name']);
        $album->setDescription($data['description']);
        $album->setDate($data['name']);
        $album->setDate(\DateTime::createFromFormat('Y-m-d', $data['date']));
        if ($oldAlbumName != $album->getName()) {
            mkdir('public/' . $this->UPLOAD_DIR . $album->getName());
        }
        $this->entityManager->flush();

        return $album;
    }

    /**
     * @var Album $album
     * @throws
     */
    public function deleteAlbum($album)
    {
        $this->entityManager->remove($album);

        $this->entityManager->flush();
    }
}