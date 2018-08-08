<?php
/**
 * Created by PhpStorm.
 * User: Shevyakhov_K
 * Date: 20.07.2018
 * Time: 18:29
 */

namespace Application\Controller\Factory;

use Application\Service\AlbumManager;
use Application\Service\CommanderManager;
use Application\Service\CounterManager;
use Application\Service\FlagManager;
use Application\Service\OrderManager;
use Application\Service\PartnerManager;
use Application\Service\PhotoManager;
use Application\Service\ShopItemGistManager;
use Application\Service\ShopItemManager;
use Application\Service\ShopItemParameterManager;
use Application\Service\TestimonialManager;
use Application\Service\UserManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\AdminController;
use Zend\Session\SessionManager;

class AdminControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $sessionManager = $container->get(SessionManager::class);
        $userManager = $container->get(UserManager::class);
        $testimonialManager = $container->get(TestimonialManager::class);
        $commanderManager = $container->get(CommanderManager::class);
        $partnerManager = $container->get(PartnerManager::class);
        $counterManager = $container->get(CounterManager::class);
        $albumManager = $container->get(AlbumManager::class);
        $photoManager = $container->get(PhotoManager::class);
        $shopItemManager = $container->get(ShopItemManager::class);
        $shopItemGistManager = $container->get(ShopItemGistManager::class);
        $shopItemParameterManager = $container->get(ShopItemParameterManager::class);
        $shopOrderManager = $container->get(OrderManager::class);
        $flagManager = $container->get(FlagManager::class);
        // Инстанцируем контроллер и внедряем зависимости.
        return new AdminController(
            $entityManager,
            $userManager,
            $sessionManager,
            $testimonialManager,
            $commanderManager,
            $partnerManager,
            $counterManager,
            $albumManager,
            $photoManager,
            $shopItemManager,
            $shopItemGistManager,
            $shopItemParameterManager,
            $shopOrderManager,
            $flagManager
        );
    }
}