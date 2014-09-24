<?php

namespace Drupal\form_builder\Helper;

use Drupal\form_builder\FormCenter\DrupalFormLayout;
use Drupal\form_builder\FormEntity;

class FormEntityFixer
{

    public function fix(FormEntity $form)
    {
        // Must have uuidGenerator, layoutOptions
        $form->setUuidGenerator(form_builder_manager()->getUuidGenerator());
        $form->setLayoutOption(form_builder_manager()->getFormLayoutOptions());

        foreach (['entity_types', 'form_fields', 'layout_options', 'form_listeners'] as $key) {
            if (!empty($form->{$key}) && is_string($form->{$key})) {
                $form->{$key} = json_decode($form->{$key}, true);
            }
        }

        $this->fixEntityTypes($form);
        $this->fixFormLayout($form);
        $this->fixFormLayoutOptions($form);
        $this->fixFormFields($form);
    }

    private function fixEntityTypes(FormEntity $form)
    {
        $form->entity_types = empty($form->entity_types) ? [] : $form->entity_types;
        foreach ($form->entity_types as $entityTypeName) {
            $entityType = form_builder_manager()->getEntityType($entityTypeName);
            $form->addEntityType($entityType);
        }
        unset($form->entity_types);
    }

    private function fixFormLayout(FormEntity $form)
    {
        $form->setLayout(new DrupalFormLayout());
    }

    private function fixFormLayoutOptions(FormEntity $form)
    {
        if (empty($form->layout_options)) {
            return;
        }

        $form->setLayoutOption($layoutOptions = form_builder_manager()->getFormLayoutOptions());
        $layoutOptions->setSubmitText($form->layout_options['submitText']);
        $layoutOptions->setConfirmationMessage($form->layout_options['confirmationMessage']);
        foreach ($form->layout_options['pages'] as $pageUuid => $pageInfo) {
            $title = isset($pageInfo['title']) ? $pageInfo['title'] : '';
            $description = isset($pageInfo['description']) ? $pageInfo['description'] : '';
            $help = isset($pageInfo['help']) ? $pageInfo['help'] : '';
            $weight = isset($pageInfo['weight']) ? $pageInfo['weight'] : null;
            $layoutOptions->addPage($pageUuid, $title, $description, $help, $weight);

            if (empty($pageInfo['fields'])) {
                continue;
            }

            foreach ($pageInfo['fields'] as $fieldUuid => $fieldInfo) {
                $layoutOptions->addField($pageUuid, $fieldUuid, $fieldInfo['weight']);
            }
        }
    }

    private function fixFormFields(FormEntity $form)
    {
        if (empty($form->form_fields)) {
            return;
        }

        foreach ($form->form_fields as $entityTypeName => $fieldNames) {
            foreach ($fieldNames as $fieldName => $fieldUuid) {
                $field = form_builder_manager()->getField($entityTypeName, $fieldName);
                $form->addField($entityTypeName, $field, $fieldUuid);
            }
        }
        unset($form->form_fields);
    }

}
