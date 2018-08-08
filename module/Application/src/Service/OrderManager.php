<?php

namespace Application\Service;

use Application\Entity\Shop\ShopOrder;
use Application\Entity\Shop\ShopItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class OrderManager
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
     * @return ShopOrder
     * @throws
     */
    public function addOrder($data)
    {
        $shopItems = new ArrayCollection();
        $order = new ShopOrder();
        foreach ($data['items'] as $shopItemId) {
            $shopItem = $this->entityManager->getRepository(ShopItem::class)->find($shopItemId);
            $shopItems[] = $shopItem;
        }
        $order->setName($data['user-name']);
        $order->setEmail($data['user-email']);
        $order->setPhoneNumber($data['user-phone']);
        $order->setPublishTimee(\DateTime::createFromFormat('Y-m-d H-i-s', date("Y-m-d H-i-s")));
        $order->setShopItems($shopItems);
        $order->setIsClosed(false);

        $this->entityManager->persist($order);

        $this->entityManager->flush();

        return $order;
    }

    /**
     * @var ShopOrder $order
     * @return ShopOrder
     * @throws
     */
    public function closeOrder($order)
    {
        $order->setIsClosed(true);

        $this->entityManager->persist($order);

        $this->entityManager->flush();
        return $order;
    }
}