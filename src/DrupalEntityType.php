<?php

namespace Drupal\form_builder;

use GO1\FormCenter\Entity\Type\EntityTypeBase;

class DrupalEntityType extends EntityTypeBase
{

    /** @var array */
    private $drupalEntityTypeInfo;

    /** @var array */
    private $drupalBundleInfo;

    protected function getDefaultStorageHandler()
    {
        return new DrupalEntityStorageHandler();
    }

    function getDrupalEntityTypeInfo()
    {
        return $this->drupalEntityTypeInfo;
    }

    function setDrupalEntityTypeInfo($drupalEntityTypeInfo)
    {
        $this->drupalEntityTypeInfo = $drupalEntityTypeInfo;
        return $this;
    }

    function getDrupalBundleInfo()
    {
        return $this->drupalBundleInfo;
    }

    function setDrupalBundleInfo($drupalBundleInfo)
    {
        $this->drupalBundleInfo = $drupalBundleInfo;
        return $this;
    }

    public function getFields()
    {
        list(, $entityTypeName, $bundleName) = explode('.', $this->getName());
        foreach (field_info_instances($entityTypeName, $bundleName) as $fieldInfo) {
            $this->addField($this->drupalArrayToField($fieldInfo));
        }
        return $this->fields;
    }

    private function drupalArrayToField(array $fieldInfo)
    {
        $field = new DrupalField();
        $field->setName($fieldInfo['field_name']);
        $field->setHumanName($fieldInfo['label']);
        $field->setEntityType($this);
        $field->setDerivable(false);
        $field->setRequired(isset($fieldInfo['required']) ? $fieldInfo['required'] : false);
        # $field->setFieldType($fieldType);
        # $field->setFieldOptions($fieldOptions);
        $field->setDrupalFieldInfo($fieldInfo);
        return $field;
    }

}
