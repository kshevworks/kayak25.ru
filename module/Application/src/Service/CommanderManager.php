<?php

namespace Application\Service;

use Application\Entity\Commander;
use Doctrine\ORM\EntityManager;

class CommanderManager
{
    /** @var EntityManager */
    private $entityManager;

    private $viewRenderer;

    private $config;

    private $saveToDir = '/img/uploads/commander_avatars/';

    public function __construct($entityManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * @var array $data
     * @return Commander
     * @throws
     */
    public function addCommander($data)
    {
        $commander = new Commander();

        $commander->setName($data['name']);
        $commander->setDescription($data['description']);
        $commander->setPhoto($this->getImagePathByName($data['photo']['name']));

        $this->entityManager->persist($commander);

        $this->entityManager->flush();

        return $commander;
    }

    /**
     * @var Commander $commander
     * @var array $data
     * @return Commander
     * @throws
     */
    public function updateCommander($commander, $data)
    {
        $commander->setName($data['name']);
        $commander->setDescription($data['description']);
        $commander->setPhoto($this->getImagePathByName($data['name']));

        $this->entityManager->flush();

        return $commander;
    }

    /**
     * @var Commander $commander
     * @throws
     */
    public function deleteCommander($commander)
    {
        $this->entityManager->remove($commander);

        $this->entityManager->flush();
    }

    public function getImagePathByName($fileName)
    {
        // Принимаем меры предосторожности, чтобы сделать файл безопасным.
        $fileName = str_replace("/", "", $fileName);  // Убираем слеши.
        $fileName = str_replace("\\", "", $fileName); // Убираем обратные слеши.

        // Возвращаем сцепленные имя каталога и имя файла.
        return $this->saveToDir . $fileName;
    }
}