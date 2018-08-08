<?php
/**
 * Created by PhpStorm.
 * User: Shevyakhov_K
 * Date: 20.07.2018
 * Time: 18:29
 */

namespace Application\Controller\Factory;

use Application\Service\OrderManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\IndexController;


class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $orderManager = $container->get(OrderManager::class);


        // Инстанцируем контроллер и внедряем зависимости.
        return new IndexController($entityManager, $orderManager);
    }
}