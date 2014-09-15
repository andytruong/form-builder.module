<?php

namespace Drupal\form_builder\FormCenter;

use GO1\FormCenter\Field\FieldValueItemInterface;
use GO1\FormCenter\Field\Type\FieldTypeBase;

class DrupalFieldType extends FieldTypeBase
{

    private $drupalFieldTypeInfo;

    public function __construct()
    {
        $this->setName('drupal.abstract_field');
        $this->setHumanName('[Drupal] Abstract field');
    }

    function getDrupalFieldTypeInfo()
    {
        return $this->drupalFieldTypeInfo;
    }

    function setDrupalFieldTypeInfo($drupalFieldTypeInfo)
    {
        $this->drupalFieldTypeInfo = $drupalFieldTypeInfo;
        return $this;
    }

    public function isEmpty(FieldValueItemInterface $fieldValueItem)
    {

    }

    public function validate(FieldValueItemInterface $fieldValueItem)
    {

    }

}
