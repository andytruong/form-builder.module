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

    public function convertEntity(FormEntity $form)
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

        foreach ($form->getLayoutOptions()->getPages() as $pageUuid => $pageInfo) {
            unset($array['layoutOptions']['uuid_generator']);
            $array['layoutOptions'][$pageUuid] = $pageInfo;
            $array['layoutOptions'][$pageUuid]['title'] = empty($array['layoutOptions'][$pageUuid]['title']) ? $pageUuid : $array['layoutOptions'][$pageUuid]['title'];
            foreach ($array['layoutOptions'][$pageUuid]['fields'] as $fieldUuid => $fieldOptions) {
                /* @var $fieldOptions FieldOptions */
                $array['layoutOptions'][$pageUuid]['fields'][$fieldUuid] = (new Serializer())->toArray($fieldOptions);
            }
        }

        return $array;
    }

}
