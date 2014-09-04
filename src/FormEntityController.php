<?php

namespace Drupal\form_builder;

use EntityAPIController;
use GO1\FormCenter\Manager\Manager;

class FormEntityController extends EntityAPIController
{

    /** @var Manager */
    private $manager;

    public function getManager()
    {
        if (null === $this->manager) {
            $this->manager = form_builder_manager();
        }
        return $this->manager;
    }

    public function load($ids = array(), $conditions = array())
    {
        $entities = parent::load($ids, $conditions);
        foreach ($entities as &$entity) {
            $this->fixEntity($entity);
        }
        return $entities;
    }

    private function fixEntity(FormEntity $entity)
    {
        /* @var $entity FormEntity */
        foreach (array('entity_types', 'form_fields', 'layout_options', 'listeners') as $key) {
            if (!empty($entity->{$key})) {
                $entity->{$key} = json_decode($entity->{$key}, true);
            }
        }

        // Fix entity types
        if (!empty($entity->entity_types)) {
            foreach ($entity->entity_types as $entity_type) {
                kpr($entity_type);
                exit;
            }
        }

        // fix form fields
        if (!empty($entity->form_fields)) {
            foreach ($entity->form_fields as $fieldUuid => $fieldName) {
                // $field = $this->getManager()->getFieldType($name);
            }
        }
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
