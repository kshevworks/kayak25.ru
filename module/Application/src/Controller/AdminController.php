<?php

namespace Application\Controller;


use Application\Entity\Album;
use Application\Entity\Commander;
use Application\Entity\Counter;
use Application\Entity\Flag;
use Application\Entity\Partner;
use Application\Entity\Photo;
use Application\Entity\Shop\ShopItem;
use Application\Entity\Shop\ShopItemGist;
use Application\Entity\Shop\ShopItemParameter;
use Application\Entity\Shop\ShopOrder;
use Application\Entity\Testimonial;
use Application\Entity\User;
use Application\Form\AlbumForm;
use Application\Form\CommanderForm;
use Application\Form\CounterForm;
use Application\Form\PartnerForm;
use Application\Form\PhotoForm;
use Application\Form\ShopItemForm;
use Application\Form\ShopItemGistForm;
use Application\Form\ShopItemParameterForm;
use Application\Form\TestimonialForm;
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
use Application\Form\UserForm;
use Application\Form\PasswordChangeForm;
use Doctrine\ORM\EntityManager;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\SessionManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;


class AdminController extends AbstractActionController
{
    /** @var EntityManager */
    private $entityManager;

    /** @var UserManager */
    private $userManager;

    /** @var SessionManager */
    private $sessionManager;

    /** @var TestimonialManager */
    private $testimonialManager;

    /** @var CommanderManager */
    private $commanderManager;

    /** @var PartnerManager */
    private $partnerManager;

    /** @var CounterManager */
    private $counterManager;

    /** @var AlbumManager */
    private $albumManager;

    /** @var PhotoManager */
    private $photoManager;

    /** @var ShopItemManager */
    private $shopItemManager;

    /** @var ShopItemGistManager */
    private $shopItemGistManager;

    /** @var ShopItemParameterManager */
    private $shopItemParameterManager;

    /** @var OrderManager */
    private $shopOrderManager;

    /** @var FlagManager */
    private $flagManager;

    public function __construct($entityManager, $userManager, $sessionManager, $testimonialManager, $commanderManager, $partnerManager, $counterManager, $albumManager, $photoManager, $shopItemManager, $shopItemGistManager, $shopItemParameterManager, $shopOrderManager, $flagManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->sessionManager = $sessionManager;
        $this->testimonialManager = $testimonialManager;
        $this->commanderManager = $commanderManager;
        $this->partnerManager = $partnerManager;
        $this->counterManager = $counterManager;
        $this->albumManager = $albumManager;
        $this->photoManager = $photoManager;
        $this->shopItemManager = $shopItemManager;
        $this->shopItemGistManager = $shopItemGistManager;
        $this->shopItemParameterManager = $shopItemParameterManager;
        $this->shopOrderManager = $shopOrderManager;
        $this->flagManager = $flagManager;
    }

    public function adminAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $flag = $this->entityManager->getRepository(Flag::class)->find(1);
        return new ViewModel(['flag' => $flag]);
    }


    /****USER SECTION****/
    public function usersAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $page = $this->params()->fromQuery('page', 1);


        $query = $this->entityManager->getRepository(User::class)->findAllUsers();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'users' => $paginator
        ]);
    }

    public function addUserAction()
    {
        // Create user form
        $this->layout()->setTemplate('layout/admin_layout');

        $form = new UserForm('create', $this->entityManager);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Add user.
                $user = $this->userManager->addUser($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('users',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editUserAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create user form
        $form = new UserForm('update', $this->entityManager, $user);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Update the user.
                $this->userManager->updateUser($user, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('users',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        } else {
            $form->setData(array(
                'full_name' => $user->getFullName(),
                'email' => $user->getEmail(),
                'status' => $user->getStatus(),
            ));
        }

        return new ViewModel(array(
            'user' => $user,
            'form' => $form
        ));
    }

    public function changePasswordAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create "change password" form
        $form = new PasswordChangeForm('change');

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Try to change password.
                if (!$this->userManager->changePassword($user, $data)) {
                    $this->flashMessenger()->addErrorMessage(
                        'Sorry, the old password is incorrect. Could not set the new password.');
                } else {
                    $this->flashMessenger()->addSuccessMessage(
                        'Changed the password successfully.');
                }

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-user', 'id' => $user->getId()]);
            }
        }

        return new ViewModel([
            'user' => $user,
            'form' => $form
        ]);
    }

    public function viewUserAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'user' => $user
        ]);
    }


    /****TESTIMONIAL SECTION****/
    public function testimonialsAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(Testimonial::class)->findAllTestimonials();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'testimonials' => $paginator
        ]);
    }

    public function viewTestimonialAction()
    {
        /** @var Response $response */
        $response = $this->getResponse();


        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $$response->setStatusCode(404);
            return null;
        }

        $testimonial = $this->entityManager->getRepository(Testimonial::class)
            ->find($id);

        if ($testimonial == null) {
            $response->setStatusCode(404);
            return null;
        }

        return new ViewModel([
            'testimonial' => $testimonial
        ]);
    }

    public function addTestimonialAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        $form = new TestimonialForm('create', $this->entityManager);
        // Check if user has submitted the form
        if ($request->isPost()) {


            // Fill in the form with POST data
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                $testimonial = $this->testimonialManager->addTestimonial($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-testimonial', 'id' => $testimonial->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editTestimonialAction()
    {
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();


        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $response->setStatusCode(404);
            return;
        }

        $testimonial = $this->entityManager->getRepository(Testimonial::class)
            ->find($id);

        if ($testimonial == null) {
            $response->setStatusCode(404);
            return;
        }


        $form = new TestimonialForm('update', $this->entityManager, $testimonial);


        if ($request->isPost()) {


            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();


                $this->testimonialManager->updateTestimonial($testimonial, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-testimonial', 'id' => $testimonial->getId()]);
            }
        } else {
            $form->setData(array(
                'author' => $testimonial->getAuthor(),
                'description' => $testimonial->getDescription(),
                'text' => $testimonial->getText(),
                'photo' => $testimonial->getPhoto()
            ));
        }

        return new ViewModel(array(
            'testimonial' => $testimonial,
            'form' => $form
        ));
    }

    public function deleteTestimonialAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        /** @var Testimonial $testimonial */
        $testimonial = $this->entityManager->getRepository(Testimonial::class)
            ->find($id);

        if ($testimonial == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->testimonialManager->deleteTestimonial($testimonial);
        $this->flashMessenger()->addSuccessMessage($testimonial->getAuthor() . '\'s review successfully deleted from datadase');
        return $this->redirect()->toRoute('admin',
            ['action' => 'testimonials']);
    }

    /****COMMANDER SECTION****/
    public function commandersAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(Commander::class)
            ->findAllCommanders();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'commanders' => $paginator
        ]);
    }

    public function viewCommanderAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $commander = $this->entityManager->getRepository(Commander::class)
            ->find($id);

        if ($commander == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'commander' => $commander
        ]);
    }

    public function addCommanderAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');

        $form = new CommanderForm('create', $this->entityManager);
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {


            // Fill in the form with POST data
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                $commander = $this->commanderManager->addCommander($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-commander', 'id' => $commander->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editCommanderAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        /** @var Commander $commander */
        $commander = $this->entityManager->getRepository(Commander::class)
            ->find($id);

        if ($commander == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }


        $form = new CommanderForm('update', $this->entityManager, $commander);


        if ($this->getRequest()->isPost()) {


            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();


                $this->commanderManager->updateCommander($commander, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-commander', 'id' => $commander->getId()]);
            }
        } else {
            $form->setData(array(
                'name' => $commander->getName(),
                'description' => $commander->getDescription(),
                'photo' => $commander->getPhoto()
            ));
        }

        return new ViewModel(array(
            'commander' => $commander,
            'form' => $form
        ));
    }

    public function deleteCommanderAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $commander = $this->entityManager->getRepository(Commander::class)
            ->find($id);

        if ($commander == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->commanderManager->deleteCommander($commander);
        $this->flashMessenger()->clearCurrentMessages();
        $this->flashMessenger()->addSuccessMessage($commander->getName() . '\'s info successfully deleted from datadase');
        return $this->redirect()->toRoute('admin',
            ['action' => 'commanders']);
    }


    /****PARTNER SECTION****/
    public function partnersAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(Partner::class)
            ->findAllPartners();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'partners' => $paginator
        ]);
    }

    public function addPartnerAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');

        $form = new PartnerForm('create', $this->entityManager);
        // Check if user has submitted the form


        if ($this->getRequest()->isPost()) {


            // Fill in the form with POST data
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);
            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                $partner = $this->partnerManager->addPartner($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'partners']);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editPartnerAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        /** @var Commander $commander */
        $partner = $this->entityManager->getRepository(Partner::class)
            ->find($id);

        if ($partner == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }


        $form = new PartnerForm('update', $this->entityManager, $partner);


        if ($this->getRequest()->isPost()) {


            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();


                $this->partnerManager->updatePartner($partner, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'partners']);
            }
        } else {
            $form->setData(array(
                'name' => $partner->getName(),
                'img' => $partner->getImg()
            ));
        }

        return new ViewModel(array(
            'partner' => $partner,
            'form' => $form
        ));
    }


    public function deletePartnerAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $partner = $this->entityManager->getRepository(Partner::class)
            ->find($id);

        if ($partner == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->commanderManager->deleteCommander($partner);
        $this->flashMessenger()->clearCurrentMessages();
        $this->flashMessenger()->addSuccessMessage($partner->getName() . '\'s info successfully deleted from datadase');
        return $this->redirect()->toRoute('admin',
            ['action' => 'partners']);
    }


    /****COUNTERS SECTION****/
    public function countersAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(Counter::class)
            ->findAllCounters();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'counters' => $paginator,
            'count' => count($query->getResult())
        ]);
    }

    public function addCounterAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');

        $form = new CounterForm('create', $this->entityManager);
        // Check if user has submitted the form


        if ($this->getRequest()->isPost()) {


            // Fill in the form with POST data
            $request = $this->getRequest();
            $data = $request->getPost();

            $form->setData($data);
            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                $counter = $this->counterManager->addCounter($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'counters']);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editCounterAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $counter = $this->entityManager->getRepository(Counter::class)
            ->find($id);

        if ($counter == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }


        $form = new CounterForm('update', $this->entityManager, $counter);


        if ($this->getRequest()->isPost()) {


            $request = $this->getRequest();
            $data = $request->getPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();


                $this->counterManager->updateCounter($counter, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'counters']);
            }
        } else {
            $form->setData(array(
                'name' => $counter->getName(),
                'speed' => $counter->getSpeed(),
                'count' => $counter->getCount(),
                'icon' => $counter->getIcon(),
            ));
        }

        return new ViewModel(array(
            'counter' => $counter,
            'form' => $form
        ));
    }


    public function deleteCounterAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $counter = $this->entityManager->getRepository(Counter::class)
            ->find($id);

        if ($counter == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->commanderManager->deleteCommander($counter);
        $this->flashMessenger()->clearCurrentMessages();
        $this->flashMessenger()->addSuccessMessage($counter->getName() . '\'s info successfully deleted from datadase');
        return $this->redirect()->toRoute('admin',
            ['action' => 'counters']);
    }


    /****ALBUM SECTION****/
    public function albumsAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(Album::class)
            ->findAllAlbums();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'albums' => $paginator
        ]);
    }

    public function viewAlbumAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $album = $this->entityManager->getRepository(Album::class)
            ->find($id);

        if ($album == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'album' => $album
        ]);
    }

    public function addAlbumAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $request = $this->getRequest();
        $form = new AlbumForm('create', $this->entityManager);
        // Check if user has submitted the form
        if ($request->isPost()) {


            // Fill in the form with POST data
            $data = $request->getPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                $album = $this->albumManager->addAlbum($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-album', 'id' => $album->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAlbumAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        /** @var Album $album */
        $album = $this->entityManager->getRepository(Album::class)
            ->find($id);

        if ($album == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }


        $form = new AlbumForm('update', $this->entityManager, $album);


        if ($this->getRequest()->isPost()) {


            $request = $this->getRequest();
            $data = $request->getPost();


            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();


                $this->albumManager->updateAlbum($album, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-album', 'id' => $album->getId()]);
            }
        } else {
            $form->setData(array(
                'name' => $album->getName(),
                'description' => $album->getDescription(),
                'date' => $album->getDate()
            ));
        }

        return new ViewModel(array(
            'album' => $album,
            'form' => $form
        ));
    }

    public function deleteAlbumAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $album = $this->entityManager->getRepository(Album::class)
            ->find($id);

        if ($album == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->commanderManager->deleteCommander($album);
        $this->flashMessenger()->clearCurrentMessages();
        $this->flashMessenger()->addSuccessMessage($album->getName() . '\'s info successfully deleted from datadase');
        return $this->redirect()->toRoute('admin',
            ['action' => 'albums']);
    }

    /****PHOTO SECTION****/
    public function addPhotoAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $albumId = (int)$this->params()->fromRoute('id', -1);

        $album = $this->entityManager->getRepository(Album::class)
            ->find($albumId);

        $form = new PhotoForm($album->getName(), $this->entityManager);
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {


            // Fill in the form with POST data
            $request = $this->getRequest();
            $data = $request->getFiles();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();


                $photos = $this->photoManager->addPhoto($data, $album);


                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-album', 'id' => $album->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form,
            'album' => $album
        ]);
    }

    public function deletePhotoAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $photo = $this->entityManager->getRepository(Photo::class)
            ->find($id);

        if ($photo == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->commanderManager->deleteCommander($photo);
        $this->flashMessenger()->clearCurrentMessages();
        $this->flashMessenger()->addSuccessMessage($photo->getId() . '\'s info successfully deleted from datadase');
        return $this->redirect()->toRoute('admin',
            ['action' => 'view-album']);
    }


    /****SHOP SECTION****/
    public function shopAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(ShopItem::class)
            ->findAllShopItems();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(12);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'shopitems' => $paginator
        ]);
    }

    public function viewShopItemAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);

        $gistForm = new ShopItemGistForm('create', $this->entityManager);
        $parameterForm = new ShopItemParameterForm('create', $this->entityManager);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $shopItem = $this->entityManager->getRepository(ShopItem::class)
            ->find($id);
        if ($shopItem == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'shopitem' => $shopItem,
            'gistForm' => $gistForm,
            'parameterForm' => $parameterForm
        ]);
    }

    public function addShopItemAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');

        $form = new ShopItemForm('create', $this->entityManager);
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {


            // Fill in the form with POST data
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                $shopItem = $this->shopItemManager->addShopItem($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-shop-item', 'id' => $shopItem->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editShopItemAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        /** @var Commander $shopItem */
        $shopItem = $this->entityManager->getRepository(ShopItem::class)
            ->find($id);

        if ($shopItem == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }


        $form = new ShopItemForm('update', $this->entityManager, $shopItem);


        if ($this->getRequest()->isPost()) {


            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();


                $this->shopItemManager->updateShopItem($shopItem, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-commander', 'id' => $shopItem->getId()]);
            }
        } else {
            $form->setData(array(
                'name' => $shopItem->getName(),
                'description' => $shopItem->getDescription(),
                'image' => $shopItem->getImage(),
                'cost' => $shopItem->getCost(),
            ));
        }

        return new ViewModel(array(
            'shopitem' => $shopItem,
            'form' => $form
        ));
    }

    public function deleteShopItemAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $shopItem = $this->entityManager->getRepository(ShopItem::class)
            ->find($id);

        if ($shopItem == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->shopItemManager->deleteShopItem($shopItem);
        $this->flashMessenger()->clearCurrentMessages();
        $this->flashMessenger()->addSuccessMessage($shopItem->getName() . '\'s info successfully deleted from datadase');
        return $this->redirect()->toRoute('admin',
            ['action' => 'shop']);
    }

    /****SHOPITEMGIST SECTION****/
    public function addShopItemGistAction()
    {
        $shopItemId = (int)$this->params()->fromRoute('id', -1);
        $shopItem = $this->entityManager->getRepository(ShopItem::class)->find($shopItemId);
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            $form = new ShopItemGistForm('create', $this->entityManager);

            // Fill in the form with POST data
            $request = $this->getRequest();
            $data = $request->getPost();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                $shopItemGist = $this->shopItemGistManager->addShopItemGist($data, $shopItem);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-shop-item', 'id' => $shopItem->getId()]);
            }
        }
    }

    public function editShopItemGistAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        /** @var Commander $commander */
        $shopItemGist = $this->entityManager->getRepository(ShopItemGist::class)
            ->find($id);

        if ($shopItemGist == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {

            $form = new ShopItemGistForm('update', $this->entityManager, $shopItemGist);
            $request = $this->getRequest();
            $data = $request->getPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();


                $this->shopItemGistManager->updateShopItemGist($shopItemGist, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-shop-item', 'id' => $shopItemGist->getShopItem()->getId()]);
            }
        }
        return;
    }

    public function deleteShopItemGistAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $shopItemGist = $this->entityManager->getRepository(ShopItemGist::class)
            ->find($id);

        if ($shopItemGist == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->shopItemManager->deleteShopItem($shopItemGist);
        return $this->redirect()->toRoute('admin',
            ['action' => 'view-shop-item', 'id' => $shopItemGist->getShopItem()->getId()]);
    }

    /****SHOPITEMPARAMETER SECTION****/
    public function addShopItemParameterAction()
    {
        $shopItemId = (int)$this->params()->fromRoute('id', -1);
        $shopItem = $this->entityManager->getRepository(ShopItem::class)->find($shopItemId);
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            $form = new ShopItemParameterForm('create', $this->entityManager);

            // Fill in the form with POST data
            $request = $this->getRequest();
            $data = $request->getPost();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                $shopItemParameter = $this->shopItemParameterManager->addShopItemParameter($data, $shopItem);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-shop-item', 'id' => $shopItem->getId()]);
            }
        }
    }

    public function editShopItemParameterAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        /** @var Commander $commander */
        $shopItemPararmeter = $this->entityManager->getRepository(ShopItemParameter::class)
            ->find($id);

        if ($shopItemPararmeter == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {

            $form = new ShopItemParameterForm('update', $this->entityManager, $shopItemPararmeter);
            $request = $this->getRequest();
            $data = $request->getPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();


                $this->shopItemParameterManager->updateShopItemParameter($shopItemPararmeter, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('admin',
                    ['action' => 'view-shop-item', 'id' => $shopItemPararmeter->getShopItem()->getId()]);
            }
        }
        return;
    }

    public function deleteShopItemParameterAction()
    {

        $this->layout()->setTemplate('layout/admin_layout');
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $shopItemPararmeter = $this->entityManager->getRepository(ShopItemParameter::class)
            ->find($id);

        if ($shopItemPararmeter == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->shopItemManager->deleteShopItem($shopItemPararmeter);
        return $this->redirect()->toRoute('admin',
            ['action' => 'view-shop-item', 'id' => $shopItemPararmeter->getShopItem()->getId()]);
    }


    /******** ORDERS ACTION ******/
    public function ordersAction()
    {
        $this->layout()->setTemplate('layout/admin_layout');
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(ShopOrder::class)
            ->findAllShopOrders();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(12);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'orders' => $paginator
        ]);
    }

    public function closeOrderAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $order = $this->entityManager->getRepository(ShopOrder::class)
            ->find($id);

        if ($order == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->shopOrderManager->closeOrder($order);
        return $this->redirect()->toRoute('admin',
            ['action' => 'orders']);
    }

    /********** FLAG SECTION */
    public function switchFlagAction()
    {

        if ($this->getRequest()->isPost()) {
            $flag = $this->entityManager->getRepository(Flag::class)
                ->find(1);
            if ($flag->getValue()) {
                $this->flagManager->setFlag($flag, false);
                return new JsonModel(['result' => 0]);
            } else {
                $this->flagManager->setFlag($flag, true);
                return new JsonModel(['result' => 1]);
            }
        }


    }


}
