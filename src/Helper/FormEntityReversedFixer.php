<?php

namespace Drupal\form_builder\Helper;

use Drupal\form_builder\FormEntity;

class FormEntityReversedFixer
{

    public function fix(FormEntity $form)
    {
        $this->fixEntityTypes($form);
        $this->fixFormFields($form);
    }

    private function fixEntityTypes(FormEntity $form)
    {
        $form->entity_types = array();
        foreach ($form->getEntityTypes() as $entityTypeName => $entityType) {
            $form->entity_types[] = $entityTypeName;
        }
        $form->entity_types = json_encode($form->entity_types);
    }

    private function fixFormFields(FormEntity $form)
    {
        $form->form_fields = array();
        foreach ($form->getFields() as $fieldUuid => $field) {
            $form->form_fields[$field->getEntityType()->getName()][$field->getName()] = $fieldUuid;
        }
        $form->form_fields = json_encode($form->form_fields);
    }

}
