<?php

namespace Drupal\form_builder\FormCenter;

use AndyTruong\Uuid\Uuid;
use Drupal\form_builder\FormEntity;
use GO1\FormCenter\Form\Layout\FormLayoutOptions;
use GO1\FormCenter\Manager\Manager as ManagerBase;

class Manager extends ManagerBase
{

    private $ran = [];

    public function __construct()
    {
        $this->setUuidGenerator(Uuid::getGenerator());
    }

    public function getEntityStorageHandlers()
    {
        $this->entityStorageHandlers = parent::getEntityStorageHandlers();

        if (!isset($this->ran[__FUNCTION__]) && $this->ran[__FUNCTION__] = true) {
            $this->entityStorageHandlers['drupal'] = new DrupalEntityStorageHandler();
        }

        return $this->entityStorageHandlers;
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
        $entityTypes = [];
        foreach (entity_get_info() as $entityName => $entityInfo) {
            if ('form_builder_form' === $entityName) {
                continue;
            }

            foreach ($entityInfo['bundles'] as $bundleName => $bundleInfo) {
                unset($entityInfo['bundles'][$bundleName]);
                $machineName = "drupal.{$entityName}.{$bundleName}";
                $entityType = new DrupalEntityType();
                $entityType->setName($machineName);
                $entityType->setHumanName($entityInfo['label'] !== $bundleInfo['label'] ? $bundleInfo['label'] . ' (' . $entityInfo['label'] . ')' : $entityInfo['label']);
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
        $this->fieldTypes = parent::getFieldTypes();

        if (!isset($this->ran[__FUNCTION__]) && $this->ran[__FUNCTION__] = true) {
            foreach (field_info_field_types() as $name => $info) {
                $this->fieldTypes['drupal.' . $name] = $this->drupalArrayToFieldType($name, $info);
            }
        }

        return $this->fieldTypes;
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
        return $hook($field = ['type' => $fieldTypeName]);
    }

    public function createForm()
    {
        $form = new FormEntity();
        $form->setLayout(new DrupalFormLayout());

        // Add default page
        $layoutOptions = new FormLayoutOptions();
        $layoutOptions->addPage('master', 'Master');
        $form->setLayoutOptions($layoutOptions);

        return $form;
    }

}
