<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


use Application\Entity\Photo;

/**
 * @ORM\Entity(repositoryClass="\Application\Repository\AlbumRepository")
 * @ORM\Table(name="Album", uniqueConstraints={@ORM\UniqueConstraint(name="album_idx", columns={"name"})})
 */
class Album
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
     * @ORM\Column(name="description")
     */
    protected $description;

    /**
     * @ORM\Column(name="date",type="date")
     */
    protected $date;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Photo", mappedBy="album")
     * @ORM\JoinColumn(name="id", referencedColumnName="albumId")
     */
    protected $photos;

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
    public function getDate()
    {
        return $this->date->format('d/m/Y');
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    public function getFirstPhoto()
    {
        return $this->photos[0];
    }

    /**
     * @param mixed $photos
     */
    public function setPhoto($photo)
    {
        $this->photos[] = $photo;
    }


    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

}