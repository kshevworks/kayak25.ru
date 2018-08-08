<?php

namespace Application\Service;

use Application\Entity\Flag;
use Doctrine\ORM\EntityManager;

class FlagManager
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
     * @var Flag $flag
     * @var bool $data
     * @return Flag
     * @throws
     */
    public function setFlag($flag, $switch)
    {
        $flag->setValue($switch);
        $this->entityManager->flush();

        return $flag;
    }
}