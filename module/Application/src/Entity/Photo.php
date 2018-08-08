<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\Album;


/**
 * @ORM\Entity(repositoryClass="\Application\Repository\PhotoRepository")
 * @ORM\Table(name="Photo")
 */
class Photo
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
     * @ORM\Column(name="url", type="text")
     */
    protected $url;

    /**
     * @ORM\Column(name="time",type="datetime")
     */
    protected $time;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Album", inversedBy="photos")
     * @ORM\JoinColumn(name="albumId", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $album;

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return mixed
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param mixed $album
     */
    public function setAlbum($album)
    {
        $this->album = $album;
        $album->setPhoto($this);
    }

}