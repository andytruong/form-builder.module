<?php

namespace Drupal\form_builder;

use AndyTruong\Uuid\Uuid;
use GO1\FormCenter\Manager\Manager as ManagerBase;

class Manager extends ManagerBase
{

    private $ran = [];

    public function __construct()
    {
        $this->setUuidGenerator(Uuid::getGenerator());
    }

    public function getEntityTypes()
    {
        $this->entityTypes = parent::getEntityTypes();

        if (!isset($this->ran[__FUNCTION__]) && $this->ran[__FUNCTION__] = true) {
            $this->entityTypes += $this->discoverEntityTypes();
        }

        return $this->entityTypes;
    }

    private function discoverEntityTypes()
    {
        $entityTypes = array();
        foreach (entity_get_info() as $entityName => $entityInfo) {
            if ('form_builder_form' === $entityName) {
                continue;
            }

            foreach ($entityInfo['bundles'] as $bundleName => $bundleInfo) {
                unset($entityInfo['bundles'][$bundleName]);
                $machineName = "drupal.{$entityName}.{$bundleName}";
                $entityType = new DrupalEntityType();
                $entityType->setName($machineName);
                $entityType->setHumanName($entityInfo['label'] !== $bundleInfo['label'] ? $entityInfo['label'] . ' ' . $bundleInfo['label'] : $entityInfo['label']);
                $entityType->setIDKey($entityInfo['entity keys']['id']);
                $entityType->setDrupalEntityTypeInfo($entityInfo);
                $entityType->setDrupalBundleInfo($bundleInfo);
                $entityTypes[$machineName] = $entityType;
            }
        }
        return $entityTypes;
    }

    public function getFieldTypes()
    {
        $fieldTypes = parent::getFieldTypes();

        if (!isset($this->ran[__FUNCTION__]) && $this->ran[__FUNCTION__] = true) {
            foreach (field_info_field_types() as $name => $info) {
                $fieldTypes['drupal.' . $name] = $this->drupalArrayToFieldType($name, $info);
            }
        }

        return $fieldTypes;
    }

    private function drupalArrayToFieldType($fieldTypeName, array $info)
    {
        $fieldType = new DrupalFieldType();
        $fieldType->setName($fieldTypeName);
        $fieldType->setHumanName($info['label']);
        $fieldType->setDescription($info['description']);
        $fieldType->setSchema($this->drupalFieldTypeSchema($fieldTypeName));
        $fieldType->setDrupalFieldTypeInfo($info);
        return $fieldType;
    }

    private function drupalFieldTypeSchema($fieldTypeName)
    {
        $fType = field_info_field_types($fieldTypeName);
        $module = $fType['module'];
        $hook = "{$module}_field_schema";
        module_load_install($module);
        return $hook($field = array('type' => $fieldTypeName));
    }

}
