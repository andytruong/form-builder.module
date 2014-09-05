<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\FormEntity;
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
        // Must have uuidGenerator
        $entity->setUuidGenerator(form_builder_manager()->getUuidGenerator());

        /* @var $entity FormEntity */
        foreach (array('entity_types', 'form_fields', 'layout_options', 'form_listeners') as $key) {
            if (!empty($entity->{$key})) {
                $entity->{$key} = json_decode($entity->{$key}, true);
            }
        }

        // Fix entity types
        if (!empty($entity->entity_types)) {
            foreach ($entity->entity_types as $uuid => $entityTypeName) {
                $entityType = form_builder_manager()->getEntityType($entityTypeName);
                $entity->addEntityType($entityType, $uuid);
            }
            unset($entity->entity_types);
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
