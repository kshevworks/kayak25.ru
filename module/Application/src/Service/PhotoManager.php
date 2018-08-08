<?php

namespace Application\Service;

use Application\Entity\Photo;
use Doctrine\ORM\EntityManager;

class PhotoManager
{
    /** @var EntityManager */
    private $entityManager;

    private $viewRenderer;

    private $config;

    private $UPLOAD_DIR = '/img/uploads/gallery/';

    private $saveToDir = 'default/';

    public function __construct($entityManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * @var array $file
     * @var \Application\Entity\Album $album
     * @return Photo
     * @throws
     */
    public function addPhoto($file, $album)
    {
        $this->saveToDir = $album->getName() . '/';

        foreach ($file['photo'] as $uplFile) {
            /** @var \Application\Entity\Photo $photo */
            $photo = new Photo();


            $photo->setAlbum($album);
            $photo->setUrl($this->getImagePathByName($uplFile['name']));
            $photo->setTime(\DateTime::createFromFormat('Y-m-d H-i-s', date("Y-m-d H-i-s")));
            $this->entityManager->persist($photo);

            $this->entityManager->flush();
        }


        return $album;
    }

    /**
     * @var Photo $photo
     * @throws
     */
    public function deletePhoto($photo)
    {
        $this->entityManager->remove($photo);

        $this->entityManager->flush();
    }

    public function getImagePathByName($fileName)
    {
        // Принимаем меры предосторожности, чтобы сделать файл безопасным.
        $fileName = str_replace("/", "", $fileName);  // Убираем слеши.
        $fileName = str_replace("\\", "", $fileName); // Убираем обратные слеши.

        // Возвращаем сцепленные имя каталога и имя файла.
        return $this->UPLOAD_DIR . $this->saveToDir . $fileName;
    }
}