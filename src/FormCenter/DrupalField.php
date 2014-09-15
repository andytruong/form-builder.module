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

    function getFieldWidget()
    {
        if (null === $this->fieldWidget) {
            $fieldWidget = new DrupalFieldWidget($this->getDrupalFieldInfo());
            $fieldWidget->setTemplateEngine(form_builder_manager()->getTemplateEngine());
            parent::setFieldWidget($fieldWidget);
        }
        return parent::getFieldWidget();
    }

    public function render(FieldOptions $fieldOptions)
    {
        return $this->getFieldWidget()->render($this, $fieldOptions);
    }

}
