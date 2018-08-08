<?php
/**
 * Created by PhpStorm.
 * User: Shevyakhov_K
 * Date: 27.07.2018
 * Time: 16:29
 */

namespace Application\Form;

use Application\Entity\Commander;
use Doctrine\ORM\EntityManager;
use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;

class CommanderForm extends Form
{
    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    /**
     * Entity manager.
     * @var EntityManager
     */
    private $entityManager = null;

    /**
     * Current user.
     * @var Commander
     */
    private $commander = null;

    public function __construct($scenario = 'create', $entityManager = null, $commander = null)
    {
        // Define form name
        parent::__construct('commander-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->setAttribute('enctype', 'multipart/form-data');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->commander = $commander;

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        // Add "email" field
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Name',
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
            'name' => 'name',
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
            'name' => 'photo',
            'required' => true,
            'filters' => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target' => './public/img/uploads/commander_avatars/',
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