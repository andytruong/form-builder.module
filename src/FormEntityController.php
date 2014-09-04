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

    /**
     * {@inheritdoc}
     */
    public function query($ids, $conditions, $revision_id = FALSE)
    {
        // I hate but I do not know better way to fix this:
        // At /admin/structure/fob-form/manage/%id, Drupal load entity before
        // executing hook_init, where composer_manager module registers composer's
        // autoloader.
        $fns = spl_autoload_functions();
        if (is_string($fns[0])) {
            composer_manager_init();
        }

        return parent::query($ids, $conditions, $revision_id);
    }

}
