<?php

namespace Application\Service;

use Application\Entity\Partner;
use Doctrine\ORM\EntityManager;

class PartnerManager
{
    /** @var EntityManager */
    private $entityManager;

    private $viewRenderer;

    private $config;

    private $saveToDir = '/img/uploads/partner_logos/';

    public function __construct($entityManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * @var array $data
     * @return Partner
     * @throws
     */
    public function addPartner($data)
    {
        $partner = new Partner();
        $partner->setName($data['name']);
        $partner->setImg($this->getImagePathByName($data['img']['name']));

        $this->entityManager->persist($partner);

        $this->entityManager->flush();

        return $partner;
    }

    /**
     * @var Partner $partner
     * @var array $data
     * @return Partner
     * @throws
     */
    public function updatePartner($partner, $data)
    {

        $partner->setName($data['name']);
        $partner->setImg($this->getImagePathByName($data['img']['name']));

        $this->entityManager->flush();

        return $partner;
    }

    /**
     * @var Partner $partner
     * @throws
     */
    public function deletePartner($partner)
    {
        $this->entityManager->remove($partner);

        $this->entityManager->flush();
    }


    /**
     * @var string $fileName
     * @return string
     */
    public function getImagePathByName($fileName)
    {
        // Принимаем меры предосторожности, чтобы сделать файл безопасным.
        $fileName = str_replace("/", "", $fileName);  // Убираем слеши.
        $fileName = str_replace("\\", "", $fileName); // Убираем обратные слеши.

        // Возвращаем сцепленные имя каталога и имя файла.
        return $this->saveToDir . $fileName;
    }
}