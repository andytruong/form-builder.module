<?php

namespace Drupal\form_builder\FormCenter;

use GO1\FormCenter\Field\FieldBase;
use GO1\FormCenter\Field\FieldOptions;
use GO1\FormCenter\Field\FieldValueItem;
use GO1\FormCenter\Field\FieldValueItemInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

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

    public function render(FieldOptions $fieldOptions, array $fieldValueItems = [])
    {
        return $this->getFieldWidget()->render($this, $fieldOptions, $fieldValueItems);
    }

    /**
     * @param FieldValueItemInterface [] $fieldValueItems
     */
    public function validate(array $fieldValueItems)
    {
        if (!empty($this->drupalFieldInfo['field'])) {
            return $this->validateDrupalField($fieldValueItems);
        }
        return $this->validateDrupalProperty($fieldValueItems);
    }

    /**
     * @param FieldValueItemInterface[] $fieldValueItems
     */
    private function validateDrupalField($fieldValueItems)
    {
        $errors = new ConstraintViolationList();

        $eTName = $this->getEntityType()->getName();
        $dETName = $this->getEntityType()->getDrupalEntityTypeName();
        $dBundleName = $this->getEntityType()->getDrupalBundleName();
        $dFName = $this->getName();
        $dField = field_info_field($dFName);
        $dFieldInstance = field_info_instance($dETName, $dFName, $dBundleName);
        $dLang = 'und';
        $dItems = array_map(function(FieldValueItem $item) {
            return $item->toArray();
        }, $fieldValueItems);

        // Validate 'required' option & the field is visible in form.
        if (!empty($dFieldInstance['required']) && null !== $fieldValueItems[0]) {
            $emptyValidator = $dField['module'] . '_field_is_empty';
            if ($emptyValidator($fieldValueItems[0], $dField)) {
                $msgRaw = '!name field is required.';
                $msg = strtr($msgRaw, ['!name' => $dFName]);
                $error = new ConstraintViolation($msg, $msgRaw, [], $fieldValueItems, $dFName . '.0', null);
                $errors->add($error);
            }
        }

        // Validate field
        $function = $dField['module'] . '_field_validate';
        if (function_exists($function)) {
            $dErrors = [];
            $function($eTName, null, $dField, $dFieldInstance, $dLang, $dItems, $dErrors);
        }

        return $errors;
    }

    /**
     * @param FieldValueItemInterface[] $fieldValueItems
     */
    private function validateDrupalProperty(array $fieldValueItems)
    {
        $errors = new ConstraintViolationList();
        if (!empty($this->drupalFieldInfo['required']) && empty($fieldValueItems[0]['value'])) {
            // The field is not visible on page
            if (null === $fieldValueItems[0]) {
                return $errors;
            }

            $msgRaw = '!name field is required.';
            $msg = strtr($msgRaw, ['!name' => $this->drupalFieldInfo['schema field']]);
            $error = new ConstraintViolation($msg, $msgRaw, [], $fieldValueItems, $this->getName() . '.0', null);
            $errors->add($error);
        }
        return $errors;
    }

}
