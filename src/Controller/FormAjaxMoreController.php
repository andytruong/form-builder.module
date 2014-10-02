<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\FormEntity;

class FormAjaxMoreController
{

    public static function pageCallback(FormEntity $form, $entityTypeName, $fieldName)
    {
        $field = $form->getEntityType($entityTypeName)->getField($fieldName);
        $widget = $field->getFieldWidget();
        $fieldOptions = $form->getLayoutOptions()->getFieldOptions($entityTypeName . '.' . $fieldName);
        $formArray = $widget->renderDrupalField($field, $fieldOptions, [], true);
        $output = drupal_render($formArray[$fieldName]['und'][0]);

        $commands = [
            [
                'command'        => 'FormBuilderMoreItem',
                'entityTypeName' => $entityTypeName,
                'fieldName'      => $fieldName,
                'output'         => $output,
            ]
        ];

        drupal_add_http_header('Content-Type', 'application/json; charset=utf-8');
        echo ajax_render($commands);
        return;

        kpr($commands);
        exit;



        ajax_deliver();
        ajax_form_callback();
        field_add_more_js();
        ajax_render($commands);
    }

}
