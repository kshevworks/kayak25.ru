<?php
/**
 * Created by PhpStorm.
 * User: Shevyakhov_K
 * Date: 24.07.2018
 * Time: 14:54
 */

namespace Application\Form;


use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect user's email, full name, password and status. The form
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters password, in 'update' scenario he/she doesn't enter password.
 */
class ShopCartForm extends Form
{
    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    /**
     * Entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Current user.
     * @var \Application\Entity\ShopCart
     */
    private $shopCart = null;

    /**
     * Constructor.
     */
    public function __construct($scenario = 'create', $entityManager = null, $shopCart = null)
    {
        // Define form name
        parent::__construct('shopCart-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->setAttribute('enctype', 'multipart/form-data');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->shopCart = $shopCart;

        $this->addElements();
        //$this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        // Add "email" field
        $this->add([
            'type' => 'text',
            'name' => 'user-phone',
            'options' => [
                'label' => 'Ваш номер: ',
            ],
        ]);

        // Add "full_name" field
        $this->add([
            'type' => 'text',
            'name' => 'user-name',
            'options' => [
                'label' => 'Ваше имя: ',
            ],
        ]);

        $this->add([
            'type' => 'email',
            'name' => 'user-email',
            'options' => [
                'label' => 'Ваш email',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create'
            ],
        ]);


        // Add the Submit button

    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    /*private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = $this->getInputFilter();

        // Add input for "email" field
        $inputFilter->add([
            'name' => 'user-email',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

        // Add input for "full_name" field
        $inputFilter->add([
            'name' => 'user-phone',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'user-name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

    }*/
}