<?php

namespace Application\Entity\Shop;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="\Application\Repository\ShopOrderRepository")
 * @ORM\Table(name="ShopOrder")
 */
class ShopOrder
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
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Shop\ShopItem", inversedBy="shoporders")
     * @ORM\JoinTable(name="shoporder_shopItem",
     *      joinColumns={@ORM\JoinColumn(name="shoporderId", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="shopItemId", referencedColumnName="id")}
     *      )
     */
    protected $shopItems;

    /**
     * @ORM\Column(name="name",type="text")
     */
    protected $name;

    /**
     * @ORM\Column(name="phoneNumber")
     */
    protected $phoneNumber;

    /**
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @ORM\Column(name="isClosed",type="boolean")
     */
    protected $isClosed;

    /**
     * @ORM\Column(name="publishTime",type="datetime")
     */
    protected $publishTime;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getShopItems()
    {
        return $this->shopItems;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPublishTime()
    {
        return $this->publishTime->format("Y-m-d H-i-s");
    }

    /**
     * @param mixed $publishDate
     */
    public function setPublishTime($publishTime)
    {
        $this->publishTime = $publishTime;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @param mixed $shopItems
     */
    public function setShopItems($shopItems)
    {
        $this->shopItems = $shopItems;
    }

    public function __construct()
    {
        $this->shopItems = new ArrayCollection();
    }

    /**
     * @return boolean
     */
    public function getIsClosed()
    {
        return $this->isClosed;
    }

    /**
     * @param boolean $isConfirmed
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
    }


}