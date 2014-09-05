<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\FormEntity;

class EntityEditController
{

    private $entity;

    public function __construct(FormEntity $entity)
    {
        $this->entity = $entity;
    }

    public static function pageCallback($entityType, $entity)
    {
        $me = new self($entity);
        return $me->render($entityType);
    }

    public function render()
    {
        angularjs_init_application('FormBuilderEntityEditingForm');

        $js = array();
        $js[] = drupal_get_path('module', 'form_builder') . '/js/entity.editing.js';
        $js[] = array(
            'type' => 'setting',
            'data' => array('FormBuilder' => $this->getRenderInfo()),
        );

        return array('#attached' => array('js' => $js));
    }

    private function getRenderInfo()
    {
        return array(
            'available' => $this->getAvailableInfo(),
            'entity'    => $this->getEntityInfo(),
        );
    }

    private function getAvailableInfo()
    {
        return array(
            'languages' => array_map(function($language) {
                    return $language->name;
                }, language_list('enabled')[1]),
            'entityTypes' => array_map(
                function($type) {
                    return $type->getHumanName();
                }, form_builder_manager()->getEntityTypes()
            ),
            'fields' => array_map(function($field) {
                    return $field->getHumanName();
                }, form_builder_manager()->getFields()),
        );
    }

    private function getEntityInfo()
    {
        $info = $this->entity->toArray();
        return $info;
    }

}
