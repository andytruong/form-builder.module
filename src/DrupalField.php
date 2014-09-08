<?php

namespace Drupal\form_builder;

use GO1\FormCenter\Field\FieldBase;

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

}
