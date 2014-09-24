<?php

namespace Drupal\form_builder\Helper;

use Drupal\form_builder\FormEntity;
use GO1\FormCenter\Field\FieldOptions;

class FormEntityReversedFixer
{

    public function fix(FormEntity $form)
    {
        $this->fixEntityTypes($form);
        $this->fixFormFields($form);
        $this->fixFormLayoutOptions($form);
    }

    private function fixEntityTypes(FormEntity $form)
    {
        $form->entity_types = [];
        foreach ($form->getEntityTypes() as $entityTypeName => $entityType) {
            $form->entity_types[] = $entityTypeName;
        }
        $form->entity_types = json_encode($form->entity_types);
    }

    private function fixFormFields(FormEntity $form)
    {
        $form->form_fields = [];
        foreach ($form->getFields() as $fieldUuid => $field) {
            $form->form_fields[$field->getEntityType()->getName()][$field->getName()] = $fieldUuid;
        }
        $form->form_fields = json_encode($form->form_fields);
    }

    private function fixFormLayoutOptions(FormEntity $form)
    {
        if (!$layoutOptions = $form->getLayoutOptions()) {
            $form->layout_options = '{}';
            return;
        }

        $form->layout_options = [];

        // base options
        $form->layout_options['submitText'] = $layoutOptions->getSubmitText();
        $form->layout_options['confirmationMessage'] = $layoutOptions->getConfirmationMessage();

        // Page fields
        foreach ($layoutOptions->getPages() as $pageUuid => $pageInfo) {
            $form->layout_options['pages'][$pageUuid] = [
                'title'       => $pageInfo['title'],
                'description' => $pageInfo['description'],
                'help'        => $pageInfo['help'],
                'weight'      => $pageInfo['weight'],
            ];

            if (!empty($pageInfo['fields'])) {
                foreach ($pageInfo['fields'] as $fieldUuid => $fieldInfo) {
                    /* @var $fieldInfo FieldOptions */
                    $form->layout_options['pages'][$pageUuid]['fields'][$fieldUuid]['weight'] = $fieldInfo->getWeight();
                }
            }
        }

        $form->layout_options = drupal_json_encode($form->layout_options);
    }

}
