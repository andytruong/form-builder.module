<?php

namespace Drupal\form_builder;

use AndyTruong\Uuid\Uuid;
use GO1\FormCenter\Manager\Manager as ManagerBase;

class Manager extends ManagerBase
{

    public function __construct()
    {
        $this->setUuidGenerator(Uuid::getGenerator());
    }

    public function getEntityTypes()
    {
        $entityTypes = parent::getEntityTypes();

        if (!isset($entityTypes['drupal.node'])) {
            foreach (entity_get_info() as $entityName => $entityInfo) {
                $entityName = "drupal.{$entityName}";
                $entityType = new DrupalEntityType();
                $entityType->setName($entityName);
                $entityType->setHumanName($entityInfo['label'] . ' (Drupal)');
                $entityType->setIDKey($entityInfo['entity keys']['id']);
                $entityTypes[$entityName] = $entityType;
            }
        }

        return $entityTypes;
    }

    public function getFieldTypes()
    {
        static $drupalFetched = false;

        $fieldTypes = parent::getFieldTypes();

        if (!$drupalFetched) {
            $drupalFetched = true;
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