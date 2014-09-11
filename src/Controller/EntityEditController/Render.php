<?php

namespace Drupal\form_builder\Controller\EntityEditController;

use AndyTruong\Serializer\Serializer;
use Drupal\form_builder\Controller\EntityEditController;
use Drupal\form_builder\Helper\FormEntityToArray;
use GO1\FormCenter\Field\FieldInterface;

class Render
{

    /** @var EntityEditController */
    private $ctrl;

    /** @var string */
    public $template;

    /** @var array */
    protected $externalJS = [
        '//ajax.googleapis.com/ajax/libs/angularjs/1.3.0-rc.0/angular.min.js',
        '//cdn.rawgit.com/ganarajpr/angular-dragdrop/master/draganddrop.js',
    ];

    public function __construct($ctrl)
    {
        $this->ctrl = $ctrl;
        $this->template = drupal_get_path('module', 'form_builder') . '/templates/form.edit.tpl.php';
    }

    public function render()
    {
        $jsSettings = array(
            'type' => 'setting',
            'data' => array('FormBuilder' => $this->getRenderInfo()),
        );

        return array(
            '#prefix'   => !empty($_GET['debug']) ? kpr($jsSettings['data']['FormBuilder'], true) : '',
            '#markup'   => theme_render_template(
                $this->template, array(
                'data' => $jsSettings['data']['FormBuilder']
            )),
            '#attached' => array(
                'css' => array(
                    drupal_get_path('module', 'form_builder') . '/css/entity.editing.css'
                ),
                'js'  => array_merge(
                    array_map(function($path) {
                        return array('type' => 'external', 'data' => $path);
                    }, $this->externalJS), array(
                    $jsSettings,
                    drupal_get_path('module', 'form_builder') . '/js/entity.editing.js'
                ))
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
        $convertor = new FormEntityToArray();
        return array(
            'languages'   => language_list('enabled')[1],
            'entityTypes' => $convertor->convertEntityTypes(form_builder_manager()->getEntityTypes()),
            'fields'      => $convertor->convertFields(array_map(
                    function($type) {
                        return $type->getName();
                    }, $this->ctrl->entity->getEntityTypes()
            )),
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
