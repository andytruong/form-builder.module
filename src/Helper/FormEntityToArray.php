<?php

namespace Drupal\form_builder\Helper;

use AndyTruong\Serializer\Serializer;
use GO1\FormCenter\Field\FieldInterface;

class FormEntityToArray
{

    public function convertEntityTypes($entityTypes)
    {
        $serializer = new Serializer();
        return array_map(function($EntityType) use ($serializer) {
            return $serializer->toArray($EntityType);
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

}
