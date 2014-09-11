<?php

namespace Drupal\form_builder;

use GO1\FormCenter\Entity\Type\EntityTypeBase;

class DrupalEntityType extends EntityTypeBase
{

    use \AndyTruong\Serializer\SerializableTrait;

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
        if (null !== $this->fields) {
            return parent::getFields();
        }

        $this->fields = array();
        list(, $entityTypeName, $bundleName) = explode('.', $this->getName());

        $propertyInfo = entity_get_property_info($entityTypeName);
        foreach ($propertyInfo['properties'] as $fieldName => $fieldInfo) {
            if ($field = $this->drupalArrayToField($fieldName, $fieldInfo)) {
                $this->addField($field);
            }
        }

        if (!empty($propertyInfo['bundles'][$bundleName]['properties'])) {
            foreach ($propertyInfo['bundles'][$bundleName]['properties'] as $fieldName => $fieldInfo) {
                if (!$this->hasField($fieldName) && ($field = $this->drupalArrayToField($fieldName, $fieldInfo))) {
                    $this->addField($field);
                }
            }
        }

        return parent::getFields();
    }

    private function drupalArrayToField($fieldName, array $fieldInfo)
    {
        // Not support read-only and token (node.type) properties for now.
        if (!isset($fieldInfo['setter callback']) || $fieldInfo['type'] === 'token') {
            return;
        }

        $field = $this->createFieldInstance();
        $field->setName($fieldName);
        $field->setHumanName($fieldInfo['label']);
        if (!empty($fieldInfo['description'])) {
            $field->setDescription($fieldInfo['description']);
        }
        $field->setRequired(isset($fieldInfo['required']) ? $fieldInfo['required'] : false);
        $field->setDrupalFieldInfo($fieldInfo);
        return $field;
    }

    private function createFieldInstance()
    {
        $field = new DrupalField();
        $field->setDerivable(false);
        $field->setEntityType($this);
        return $field;
    }

}
