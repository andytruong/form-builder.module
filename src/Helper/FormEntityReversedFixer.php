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
        foreach ($form->getEntityTypes() as $entityTypeName => $entityType) {
            $form->entity_types[] = $entityTypeName;
        }
    }

    private function fixFormFields(FormEntity $form)
    {
        foreach ($form->getFields() as $fieldUuid => $field) {
            $form->form_fields[$field->getEntityType()->getName()][$field->getName()] = $fieldUuid;
        }
        print_r([__METHOD__, __LINE__, $form->form_fields]);
        exit;
    }

}
