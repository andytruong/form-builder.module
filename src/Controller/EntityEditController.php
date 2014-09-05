<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\FormEntity;

class EntityEditController
{

    private $entity;

    public function __construct(FormEntity $entity)
    {
        $this->entity = $entity;

        angularjs_init_application('FormBuilderEntityEditingForm');
        drupal_add_js(drupal_get_path('module', 'form_builder') . '/js/entity.editing.js');
        drupal_add_js(array('FormBuilder' => array(
                'available'   => array(
                    'languages' => array_map(function($language) {
                            return $language->name;
                        }, language_list('enabled')[1]),
                    'entityTypes' => array_map(
                        function($type) {
                            return $type->getHumanName();
                        }, form_builder_manager()->getEntityTypes()
                    ),
                    'fields'      => array_map(function($field) {
                            return $field->getHumanName();
                        }, form_builder_manager()->getFields()),
                ),
                'entity' => array(
                    'title'       => $this->entity->getTitle(),
                    'status'      => $this->entity->getStatus(),
                    'entityTypes' => $this->entity->getEntityTypes(),
                ),
            )), array('type' => 'setting'));
    }

    public static function pageCallback($entityType, $entity)
    {
        $me = new self($entity);
        return $me->render($entityType);
    }

    public function render()
    {
        dsm($this->entity);
        return '…';
    }

}
