<?php

namespace Application\Entity\Shop;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="\Application\Repository\ShopItemParameterRepository")
 * @ORM\Table(name="ShopItemParameter")
 */
class ShopItemParameter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    //Enter your code below


    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Shop\ShopItem", inversedBy="parameters")
     * @ORM\JoinColumn(name="shopItemId", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $shopItem;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="value")
     */
    protected $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getShopItem()
    {
        return $this->shopItem;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $shopItem
     */
    public function setShopItem($shopItem)
    {
        $this->shopItem = $shopItem;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}