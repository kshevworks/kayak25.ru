<?php

namespace Application\Service;

use Application\Entity\Shop\ShopItem;
use Doctrine\ORM\EntityManager;

class ShopItemManager
{
    /** @var EntityManager */
    private $entityManager;

    private $viewRenderer;

    private $config;

    private $saveToDir = '/img/uploads/shop/';

    public function __construct($entityManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * @var array $data
     * @return ShopItem
     * @throws
     */
    public function addShopItem($data)
    {
        $shopItem = new ShopItem();

        $shopItem->setName($data["name"]);
        $shopItem->setDescription($data["description"]);
        $shopItem->setCost($data['cost']);
        $shopItem->setImage($this->getImagePathByName($data['image']['name']));

        $this->entityManager->persist($shopItem);

        $this->entityManager->flush();

        return $shopItem;
    }

    /**
     * @var ShopItem $shopItem
     * @var array $data
     * @return ShopItem
     * @throws
     */
    public function updateShopItem($shopItem, $data)
    {
        $shopItem->setName($data["name"]);
        $shopItem->setDescription($data["description"]);
        $shopItem->setCost($data['cost']);
        $shopItem->setImage($this->getImagePathByName($data['image']));

        $this->entityManager->flush();

        return $shopItem;
    }

    /**
     * @var ShopItem $shopItem
     * @throws
     */
    public function deleteShopItem($shopItem)
    {
        $this->entityManager->remove($shopItem);

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