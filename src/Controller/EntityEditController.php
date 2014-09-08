<?php

namespace Drupal\form_builder\Controller;

use AndyTruong\Serializer\Serializer;
use Drupal\form_builder\FormEntity;

class EntityEditController
{

    private $entity;
    private $template;

    public function __construct(FormEntity $entity)
    {
        $this->entity = $entity;
        $this->template = drupal_get_path('module', 'form_builder') . '/templates/form.edit.tpl.php';
    }

    public static function pageCallback($entityType, $entity)
    {
        $me = new self($entity);
        return $me->render($entityType);
    }

    public function render()
    {
        $js = array();
        $js[0] = drupal_get_path('module', 'form_builder') . '/js/entity.editing.js';
        $js[1] = array(
            'type' => 'setting',
            'data' => array('FormBuilder' => $this->getRenderInfo()),
        );

        return array(
            '#prefix'   => '<div ng-app="fob_entity_edit" ng-controller="HelloCtrl">',
            '#markup'   => kpr($js[1]['data']['FormBuilder'], true) . theme_render_template(
                $this->template, array(
                'data' => $js[1]['data']['FormBuilder']
            )),
            '#suffix'   => '</div>',
            '#attached' => array(
                'library' => array(
                    array('angularjs', 'angularjs')
                ),
                'js'      => $js
            )
        );
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
            'languages'   => language_list('enabled')[1],
            'entityTypes' => array_map(
                function($type) {
                    return (new Serializer())->toArray($type);
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
