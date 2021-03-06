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
class TestimonialForm extends Form
{
    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Current user.
     * @var Application\Entity\Testimonial
     */
    private $testimonial = null;

    /**
     * Constructor.
     */
    public function __construct($scenario = 'create', $entityManager = null, $testimonial = null)
    {
        // Define form name
        parent::__construct('testimonial-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->setAttribute('enctype', 'multipart/form-data');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->testimonial = $testimonial;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        // Add "email" field
        $this->add([
            'type' => 'text',
            'name' => 'author',
            'options' => [
                'label' => 'Author',
            ],
        ]);

        // Add "full_name" field
        $this->add([
            'type' => 'text',
            'name' => 'description',
            'options' => [
                'label' => 'Description',
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'text',
            'options' => [
                'label' => 'Text',
            ],
        ]);

        $this->add([
            'type' => 'file',
            'name' => 'photo',
            'attributes' => [
                'id' => 'photo',
            ],
            'options' => [
                'label' => 'Photo',
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
    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = $this->getInputFilter();

        // Add input for "email" field
        $inputFilter->add([
            'name' => 'author',
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
            'name' => 'description',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 512
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'text',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [],
        ]);

        $inputFilter->add([
            'name' => 'photo',
            'required' => true,
            'filters' => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target' => './public/img/uploads/testimonial_avatars/',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => false
                    ]
                ]
            ],
            'validators' => [
                ['name' => 'FileUploadFile'],
                [
                    'name' => 'FileMimeType',
                    'options' => [
                        'mimeType' => ['image/jpeg', 'image/png']
                    ]
                ],
                ['name' => 'FileIsImage'],
                [
                    'name' => 'FileImageSize',
                    'options' => [
                        'minWidth' => 128,
                        'minHeight' => 128,
                        'maxWidth' => 4096,
                        'maxHeight' => 4096
                    ]
                ],
            ],
        ]);

    }
}