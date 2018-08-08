<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\CommanderManager;

class CommanderManagerFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return CommanderManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');

        return new CommanderManager($entityManager, $viewRenderer, $config);
    }
}