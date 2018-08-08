<?php

namespace Application\Entity\Shop;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use NumberFormatter;
use Application\Entity\Shop\ShopItemParameter;
use Application\Entity\Shop\ShopItemGist;


/**
 * @ORM\Entity(repositoryClass="\Application\Repository\ShopItemRepository")
 * @ORM\Table(name="ShopItem")
 */
class ShopItem
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
     * @ORM\Column(name="name")
     */
    protected $name;
    /**
     * @ORM\Column(name="cost",type="integer")
     */
    protected $cost;
    /**
     * @ORM\Column(name="description")
     */
    protected $description;

    /**
     * @ORM\Column(name="image")
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Shop\ShopItemParameter", mappedBy="shopItem")
     * @ORM\JoinColumn(name="id", referencedColumnName="shopItemId")
     */
    protected $parameters;


    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Shop\ShopItemGist", mappedBy="shopItem")
     * @ORM\JoinColumn(name="id", referencedColumnName="shopItemId")
     */
    protected $gists;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Shop\ShopOrder", mappedBy="shopItems")
     */
    protected $shoporders;

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getFormattedCost()
    {
        $formatter = new NumberFormatter('ru_RU', NumberFormatter::CURRENCY, '#0');
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
        return $formatter->formatCurrency($this->cost, 'RUB');
    }

    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @return mixed
     */
    public function getGists()
    {
        return $this->gists;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @param mixed $gists
     */
    public function setGists($gists)
    {
        $this->gists = $gists;
    }

    public function setGist($gist)
    {
        $this->gists[] = $gist;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }


    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function setParameter($parameter)
    {
        $this->parameters[] = $parameter;
    }

    public function __construct()
    {
        $this->parameters = new ArrayCollection();
        $this->gists = new ArrayCollection();
    }

}