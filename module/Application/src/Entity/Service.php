<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="Service")
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="field_name")
     */
    protected $field_name;

    /**
     * @ORM\Column(name="field_data")
     */
    protected $field_data;

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * @return mixed
     */
    public function getFieldData()
    {
        return $this->field_data;
    }

    /**
     * @param mixed $field_data
     */
    public function setFieldData($field_data)
    {
        $this->field_data = $field_data;
    }
}