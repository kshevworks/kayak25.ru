<?php
/**
 * Created by PhpStorm.
 * User: Shevyakhov_K
 * Date: 25.07.2018
 * Time: 19:42
 */

namespace Application\Service;

use Application\Entity\Testimonial;
use Doctrine\ORM\EntityManager;

class TestimonialManager
{
    /** @var EntityManager */
    private $entityManager;

    private $viewRenderer;

    private $config;

    private $saveToDir = '/img/uploads/testimonial_avatars/';

    public function __construct($entityManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * @var array $data
     * @return Testimonial
     * @throws
     */
    public function addTestimonial($data)
    {
        $testimonial = new Testimonial();
        $testimonial->setAuthor($data['author']);
        $testimonial->setDescription($data['description']);
        $testimonial->setPhoto($this->getImagePathByName($data['photo']['name']));
        $testimonial->setText($data['text']);

        $this->entityManager->persist($testimonial);

        $this->entityManager->flush();

        return $testimonial;
    }

    /**
     * @var Testimonial $testimonial
     * @var array $data
     * @return Testimonial
     * @throws
     */
    public function updateTestimonial($testimonial, $data)
    {
        $testimonial->setAuthor($data['author']);
        $testimonial->setDescription($data['description']);
        $testimonial->setPhoto($this->getImagePathByName($data['photo']['name']));
        $testimonial->setText($data['text']);

        $this->entityManager->flush();

        return $testimonial;
    }

    /**
     * @var Testimonial $testimonial
     * @throws
     */
    public function deleteTestimonial($testimonial)
    {
        $this->entityManager->remove($testimonial);

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