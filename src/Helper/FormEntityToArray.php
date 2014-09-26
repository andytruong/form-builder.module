<?php

namespace Drupal\form_builder\Helper;

use AndyTruong\Serializer\Serializer;
use Drupal\form_builder\FormEntity;
use GO1\FormCenter\Field\FieldInterface;
use GO1\FormCenter\Field\FieldOptions;

class FormEntityToArray
{

    public function convertEntityTypes($entityTypes)
    {
        $convertor = $this;
        $serializer = new Serializer();
        return array_map(function($EntityType) use ($convertor, $serializer) {
            $return = $serializer->toArray($EntityType);
            if (!empty($return['fields'])) {
                foreach ($return['fields'] as $fieldName => $field) {
                    /* @var $field FieldInterface */
                    $return['fields'][$fieldName] = [
                        'name'           => $field->getName(),
                        'humanName'      => $field->getHumanName(),
                        'entityTypeName' => $field->getEntityType()->getName(),
                        'description'    => $field->getDescription(),
                    ];
                }
            }
            return $return;
        }, $entityTypes);
    }

    public function convertFields($entityTypeNames)
    {
        $fields = [];
        foreach (form_builder_manager()->getFields($entityTypeNames) as $entityTypeName => $entityFields) {
            foreach ($entityFields as $fieldName => $field) {
                /* @var $field FieldInterface */
                $fields["{$entityTypeName}.{$fieldName}"] = [
                    'name'           => $field->getName(),
                    'humanName'      => $field->getHumanName(),
                    'entityTypeName' => $field->getEntityType()->getName(),
                    'description'    => $field->getDescription(),
                ];
            }
        }
        return $fields;
    }

    public function convertForm(FormEntity $form)
    {
        $array = (new Serializer())->toArray($form);

        $array['status'] = (bool) $array['status'];

        // AngularJS friendly
        $array['entityTypes'] = array_keys($array['entityTypes']);
        foreach ($array['entityTypes'] as $i => $name) {
            unset($array['entityTypes'][$i]);
            $array['entityTypes'][$name] = true;
        }

        foreach ($array['fields'] as $fieldUuid => $field) {
            /* @var $field FieldInterface */
            $array['fields'][$fieldUuid] = [
                'entityTypeName' => $field->getEntityType()->getName(),
                'name'           => $field->getName(),
                'humanName'      => $field->getHumanName(),
            ];
        }

        // Form layout options
        $array['layoutOptions']['confirmationMessage'] = $form->getLayoutOptions()->getConfirmationMessage();
        $array['layoutOptions']['pages'] = [];

        // Form layout options -> pages
        foreach ($form->getLayoutOptions()->getPages() as $pageUuid => $pageInfo) {
            $array['layoutOptions']['pages'][$pageUuid] = $this->convertFormPage($pageUuid, $pageInfo);
        }

        unset($array['layoutOptions']['uuid_generator']);
        return $array;
    }

    private function convertFormPage($pageUuid, $pageInfo)
    {
        $return = $pageInfo;
        $return['title'] = empty($return['title']) ? $pageUuid : $return['title'];

        // Page fields
        foreach ($return['fields'] as $fieldKey => $fieldOptions) {
            /* @var $fieldOptions FieldOptions */
            $return['fields'][$fieldKey] = (new Serializer())->toArray($fieldOptions);
        }

        // Page groups
        if (!empty($return['groups'])) {
            foreach ($return['groups'] as $groupUuid => $fieldGroup) {
                $return['groups'][$groupUuid] = (new Serializer())->toArray($fieldGroup);
            }
        }

        return $return;
    }

}
