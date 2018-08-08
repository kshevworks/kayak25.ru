<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\PartnerManager;

class PartnerManagerFactory
{
    /**
     * This method creates the UserManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');

        return new PartnerManager($entityManager, $viewRenderer, $config);
    }
}