<?php

namespace Application\Service;

use Application\Entity\Shop\ShopItemGist;
use Doctrine\ORM\EntityManager;

class ShopItemGistManager
{
    /** @var EntityManager */
    private $entityManager;

    private $viewRenderer;

    private $config;

    public function __construct($entityManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * @var array $data
     * @var \Application\Entity\Shop\ShopItem $shopItem
     * @return ShopItemGist
     * @throws
     */
    public function addShopItemGist($data, $shopItem)
    {
        $shopItemGist = new ShopItemGist();

        $shopItemGist->setName($data['name']);
        $shopItemGist->setValue($data['value']);
        $shopItemGist->setShopItem($shopItem);

        $this->entityManager->persist($shopItemGist);

        $this->entityManager->flush();

        return $shopItemGist;
    }

    /**
     * @var ShopItemGist $shopItemGist
     * @var array $data
     * @return ShopItemGist
     * @throws
     */
    public function updateShopItemGist($shopItemGist, $data)
    {

        $shopItemGist->setName($data['name']);
        $shopItemGist->setValue($data['value']);
        $this->entityManager->flush();

        return $shopItemGist;
    }

    /**
     * @var ShopItemGist $shopItemGist
     * @throws
     */
    public function deleteShopItemGist($shopItemGist)
    {
        $this->entityManager->remove($shopItemGist);

        $this->entityManager->flush();
    }
}