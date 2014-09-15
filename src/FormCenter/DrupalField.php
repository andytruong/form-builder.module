<?php

namespace Drupal\form_builder\FormCenter;

use GO1\FormCenter\Field\FieldBase;
use GO1\FormCenter\Field\FieldOptions;

class DrupalField extends FieldBase
{

    private $drupalFieldInfo;

    function getDrupalFieldInfo()
    {
        return $this->drupalFieldInfo;
    }

    function setDrupalFieldInfo($drupalFieldInfo)
    {
        $this->drupalFieldInfo = $drupalFieldInfo;
        return $this;
    }

    public function render(FieldOptions $fieldOptions)
    {
        // $this->getFieldWidget()->render($this, $fieldOptions);
        return '[WIP] drupal field';
    }

}
