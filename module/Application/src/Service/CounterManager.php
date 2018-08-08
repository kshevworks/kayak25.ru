<?php

namespace Application\Service;

use Application\Entity\Counter;
use Doctrine\ORM\EntityManager;

class CounterManager
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
     * @return Counter
     * @throws
     */
    public function addCounter($data)
    {
        $counter = new Counter();
        $counter->setName($data['name']);
        $counter->setCount($data['count']);
        $counter->setSpeed($data['speed']);
        $counter->setIcon($data['icon']);


        $this->entityManager->persist($counter);

        $this->entityManager->flush();

        return $counter;
    }

    /**
     * @var Counter $counter
     * @var array $data
     * @return Counter
     * @throws
     */
    public function updateCounter($counter, $data)
    {

        $counter->setName($data['name']);
        $counter->setCount($data['count']);
        $counter->setSpeed($data['speed']);
        $counter->setIcon($data['icon']);

        $this->entityManager->flush();

        return $counter;
    }

    /**
     * @var Counter $counter
     * @throws
     */
    public function deleteCounter($counter)
    {
        $this->entityManager->remove($counter);

        $this->entityManager->flush();
    }
}