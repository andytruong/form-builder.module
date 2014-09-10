<?php

namespace Drupal\form_builder\Helper;

use Drupal\form_builder\FormEntity;

class FormEntityFixer
{

    public function fix(FormEntity $form)
    {
        // Must have uuidGenerator
        $form->setUuidGenerator(form_builder_manager()->getUuidGenerator());

        foreach (array('entity_types', 'form_fields', 'layout_options', 'form_listeners') as $key) {
            if (!empty($form->{$key}) && is_string($form->{$key})) {
                $form->{$key} = json_decode($form->{$key}, true);
            }
        }

        $this->fixEntityTypes($form);
        $this->fixFormFields($form);
    }

    private function fixEntityTypes(FormEntity $form)
    {
        $form->entity_types = empty($form->entity_types) ? array() : $form->entity_types;
        foreach ($form->entity_types as $entityTypeName) {
            $entityType = form_builder_manager()->getEntityType($entityTypeName);
            $form->addEntityType($entityType);
        }
        unset($form->entity_types);
    }

    private function fixFormFields(FormEntity $form)
    {
        if (!empty($form->form_fields)) {
            foreach ($form->form_fields as $entityTypeName => $fieldNames) {
                foreach ($fieldNames as $fieldName => $fieldUuid) {
                    $field = form_builder_manager()->getField($entityTypeName, $fieldName);
                    $form->addField($entityTypeName, $field, $fieldUuid);
                }
            }
            unset($form->form_fields);
        }
    }

}