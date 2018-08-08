<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;


use Application\Entity\Flag;
use Application\Entity\Shop\ShopItem;
use Application\Form\ShopCartForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mail;

use Application\Entity\Testimonial;
use Application\Entity\Commander;
use Application\Entity\Counter;
use Application\Entity\Partner;
use Application\Entity\Service;
use Application\Entity\Album;

class IndexController extends AbstractActionController
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    private $orderManager;

    public function __construct($entityManager, $orderManager)
    {
        $this->entityManager = $entityManager;
        $this->orderManager = $orderManager;
    }


    public function indexAction()
    {
        $flag = $this->entityManager->getRepository(Flag::class)->find(1);
        if ($flag->getValue()) {
            $this->layout()->setTemplate('layout/layout_mt');
            return new ViewModel();
        }

        $testimonials = $this->entityManager->getRepository(Testimonial::class)->findAll();
        $commanders = $this->entityManager->getRepository(Commander::class)->findAll();
        $counters = $this->entityManager->getRepository(Counter::class)->findAll();
        $partners = $this->entityManager->getRepository(Partner::class)->findAll();
        $contacts = $this->entityManager->getRepository(Service::class)->findAll();
        $albums = $this->entityManager->getRepository(Album::class)->findAll();
        $shopitems = $this->entityManager->getRepository(ShopItem::class)->findAll();

        $shopCartForm = new ShopCartForm();

        return new ViewModel([
            'testimonials' => $testimonials,
            'commanders' => $commanders,
            'counters' => $counters,
            'partners' => $partners,
            'contacts' => $contacts,
            'albums' => $albums,
            'shopitems' => $shopitems,
            'shopcartform' => $shopCartForm
        ]);


    }

    public function addOrderAction()
    {
        $request = $this->getRequest();
        $this->layout()->terminate();
        $form = new ShopCartForm();
        $error = '';
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            if (isset($_POST['grecaptcharesponse'])) {
                $result = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfoA2gUAAAAAGU7DpmxE_bBIxp6vNVb_0Rpxu7E&response=" . $data['grecaptcharesponse'] . "&remoteip=" . $_SERVER['REMOTE_ADDR']), TRUE);
                if ($result['success'] == 1) {

                    $form->setData($data);
                    if ($form->isValid()) {
                        $order = $this->orderManager->addOrder($data);
                        $error = 'OK';
                        $this->sendMail($order);
                        return new JsonModel(['result_code' => 1, 'result' => $error, 'orderNumber' => $order->getId()]);
                    }
                    $error = '(Заполнены не все поля формы)';
                    return new JsonModel(['result_code' => 0, 'result' => $error]);


                } else {
                    $error = '(Captcha invalid)';
                    return new JsonModel(['result_code' => 0, 'result' => $error]);
                }
            }
        }

    }

    /**
     * @param \Application\Entity\Shop\ShopOrder $order
     */
    public function sendMail($order)
    {
        $name = $order->getName();
        $email_address = $order->getEmail();
        $phone = $order->getPhoneNumber();
        $mail = new Mail\Message();
        $subject = 'Order #' . $order->getId() . ' from ' . $order->getPublishTime();
        $body = "You have received a new order from your website.\n\n" . "Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nOrder:";
        $shItemList = '';
        foreach ($order->getShopItems() as $shopItem) {
            $shItemList .= $shopItem->getName() . "\n\n";
        }
        $body .= $shItemList;
        $mail->setBody($body);
        $mail->setFrom($email_address, $name);
        $mail->addTo('saintdarkvl@gmail.com', 'Kayak25');
        $mail->setSubject($subject);

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }

}
