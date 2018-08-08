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
class PhotoForm extends Form
{
    /** @var \Application\Entity\Album */
    private $album;

    private $entityManager;

    /**
     * Constructor.
     */
    public function __construct($album, $entityManager)
    {
        // Define form name
        parent::__construct('photo-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->setAttribute('enctype', 'multipart/form-data');

        $this->album = $album;
        $this->entityManager = $entityManager;

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
        $targetDir = './public/img/uploads/gallery/' . $this->album . '/';
        $inputFilter->add([
            'name' => 'photo',
            'required' => true,
            'filters' => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target' => $targetDir,
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