<?php

namespace Drupal\form_builder\Controller;

use AndyTruong\Serializer\Serializer;
use Drupal\form_builder\FormEntity;
use GO1\FormCenter\Field\FieldInterface;

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
        $js[0] = array('data' => '//ajax.googleapis.com/ajax/libs/angularjs/1.3.0-rc.0/angular.min.js', array('external' => true));
        $js[1] = drupal_get_path('module', 'form_builder') . '/js/entity.editing.js';
        $js[2] = array(
            'type' => 'setting',
            'data' => array('FormBuilder' => $this->getRenderInfo()),
        );

        return array(
            // '#prefix'   => kpr($js[1]['data']['FormBuilder'], true),
            '#markup'   => theme_render_template(
                $this->template, array(
                'data' => $js[2]['data']['FormBuilder']
            )),
            '#attached' => array(
                'js' => $js
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
        $allEntityTypes = array_map(function($type) {
            return (new Serializer())->toArray($type);
        }, form_builder_manager()->getEntityTypes());

        $addedEntityTypeNames = array_map(function($type) {
            return $type->getName();
        }, $this->entity->getEntityTypes());

        $fields = array_map(function(FieldInterface $field) {
            return (new Serializer())->toArray($field);
        }, form_builder_manager()->getFields($addedEntityTypeNames));

        return array(
            'languages'   => language_list('enabled')[1],
            'entityTypes' => $allEntityTypes,
            'fields'      => $fields,
        );
    }

    private function getEntityInfo()
    {
        $info = $this->entity->toArray();
        return $info;
    }

}
