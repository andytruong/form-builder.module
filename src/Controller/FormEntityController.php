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

    public function fixEntity(FormEntity $form)
    {
        // Must have uuidGenerator
        $form->setUuidGenerator(form_builder_manager()->getUuidGenerator());

        /* @var $form FormEntity */
        foreach (array('entity_types', 'form_fields', 'layout_options', 'form_listeners') as $key) {
            if (!empty($form->{$key}) && is_string($form->{$key})) {
                $form->{$key} = json_decode($form->{$key}, true);
            }
        }

        // Fix entity types
        $form->entity_types = empty($form->entity_types) ? array() : $form->entity_types;
        foreach ($form->entity_types as $entityTypeName) {
            $entityType = form_builder_manager()->getEntityType($entityTypeName);
            $form->addEntityType($entityType);
        }
        unset($form->entity_types);

        // fix form fields
        if (!empty($form->form_fields)) {
            foreach ($form->form_fields as $entityTypeName => $fieldNames) {
                foreach ($fieldNames as $fieldName) {
                    $field = $this->getManager()->getField($entityTypeName, $fieldName);
                    $form->addField($entityTypeName, $field);
                }
            }
            unset($form->form_fields);
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
