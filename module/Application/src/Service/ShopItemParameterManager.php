<?php

namespace Application\Service;

use Application\Entity\Shop\ShopItemParameter;
use Doctrine\ORM\EntityManager;

class ShopItemParameterManager
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
     * @return ShopItemParameter
     * @throws
     */
    public function addShopItemParameter($data, $shopItem)
    {
        $shopItemParameter = new ShopItemParameter();

        $shopItemParameter->setName($data['name']);
        $shopItemParameter->setValue($data['value']);
        $shopItemParameter->setShopItem($shopItem);

        $this->entityManager->persist($shopItemParameter);

        $this->entityManager->flush();

        return $shopItemParameter;
    }

    /**
     * @var ShopItemParameter $shopItemParameter
     * @var array $data
     * @return ShopItemParameter
     * @throws
     */
    public function updateShopItemParameter($shopItemParameter, $data)
    {
        $shopItemParameter->setName($data['name']);
        $shopItemParameter->setValue($data['value']);
        $this->entityManager->flush();

        return $shopItemParameter;
    }

    /**
     * @var ShopItemParameter $shopItemParameter
     * @throws
     */
    public function deleteShopItemParameter($shopItemParameter)
    {
        $this->entityManager->remove($shopItemParameter);

        $this->entityManager->flush();
    }
}