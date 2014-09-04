<?php

namespace Drupal\form_builder;

use EntityAPIController;

class FormEntityController extends EntityAPIController
{

    public function load($ids = array(), $conditions = array())
    {
        $entities = parent::load($ids, $conditions);
        foreach ($entities as &$entity) {
            foreach (array('entity_types', 'form_fields', 'layout_options', 'listeners') as $key) {
                if (!empty($entity->{$key})) {
                    $entity->{$key} = json_decode($entity->{$key}, true);
                }
            }
        }
        return $entities;
    }

}
