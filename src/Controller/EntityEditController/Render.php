<?php

namespace Drupal\form_builder\Controller\EntityEditController;

use AndyTruong\Serializer\Serializer;
use Drupal\form_builder\Controller\EntityEditController;
use GO1\FormCenter\Field\FieldInterface;

class Render
{

    /** @var EntityEditController */
    private $ctrl;

    /** @var string */
    public $template;

    public function __construct($ctrl)
    {
        $this->ctrl = $ctrl;
        $this->template = drupal_get_path('module', 'form_builder') . '/templates/form.edit.tpl.php';
    }

    public function render()
    {
        $js = array();
        $js[0] = array('type' => 'external', 'data' => '//ajax.googleapis.com/ajax/libs/angularjs/1.3.0-rc.0/angular.min.js');
        $js[1] = array('type' => 'external', 'data' => '//cdn.rawgit.com/ganarajpr/angular-dragdrop/master/draganddrop.js');
        $js[3] = drupal_get_path('module', 'form_builder') . '/js/entity.editing.js';
        $js[4] = array(
            'type' => 'setting',
            'data' => array('FormBuilder' => $this->getRenderInfo()),
        );

        return array(
            '#prefix'   => !empty($_GET['debug']) ? kpr($js[4]['data']['FormBuilder'], true) : '',
            '#markup'   => theme_render_template(
                $this->template, array(
                'data' => $js[4]['data']['FormBuilder']
            )),
            '#attached' => array(
                'css' => array(
                    drupal_get_path('module', 'form_builder') . '/css/entity.editing.css'
                ),
                'js'  => $js
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
        $serializer = new Serializer();
        $allEntityTypes = array_map(function($type) use ($serializer) {
            return $serializer->toArray($type);
        }, form_builder_manager()->getEntityTypes());

        $addedEntityTypeNames = array_map(function($type) {
            return $type->getName();
        }, $this->ctrl->entity->getEntityTypes());

        $fields = array();
        foreach (form_builder_manager()->getFields($addedEntityTypeNames) as $entityTypeName => $entityFields) {
            foreach ($entityFields as $fieldName => $field) {
                /* @var $field FieldInterface */
                $fields["{$entityTypeName}.{$fieldName}"] = [
                    'name'           => $field->getName(),
                    'humanName'      => $field->getHumanName(),
                    'entityTypeName' => $field->getEntityType()->getName(),
                ];
            }
        }

        return array(
            'languages'   => language_list('enabled')[1],
            'entityTypes' => $allEntityTypes,
            'fields'      => $fields,
        );
    }

    private function getEntityInfo()
    {
        $array = (new Serializer())->toArray($this->ctrl->entity);

        $array['status'] = (bool) $array['status'];

        // AngularJS friendly
        $array['entityTypes'] = array_keys($array['entityTypes']);
        foreach ($array['entityTypes'] as $i => $name) {
            unset($array['entityTypes'][$i]);
            $array['entityTypes'][$name] = true;
        }

        foreach ($array['fields'] as $uuid => $field) {
            /* @var $field FieldInterface */
            $array['fields'][$uuid] = [
                'entityTypeName' => $field->getEntityType()->getName(),
                'name'           => $field->getName(),
                'humanName'      => $field->getHumanName()
            ];
        }

        return $array;
    }

}
