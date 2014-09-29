<?php

namespace Drupal\form_builder\Controller\EntityEditController;

use Drupal\form_builder\Controller\EntityEditController;
use Drupal\form_builder\Helper\FormEntityToArray;

class Render
{

    /** @var EntityEditController */
    private $ctrl;

    /** @var string */
    public $template;

    /** @var array */
    protected $externalJS = [
        '//ajax.googleapis.com/ajax/libs/angularjs/1.3.0-rc.3/angular.min.js',
        '//cdn.rawgit.com/ganarajpr/angular-dragdrop/89d4fcaedc5023527aa1e542fd84adc21f30c70b/draganddrop.js',
    ];

    public function __construct($ctrl)
    {
        $this->ctrl = $ctrl;
        $this->template = drupal_get_path('module', 'form_builder') . '/templates/form.edit.tpl.php';
    }

    public function render()
    {
        $jsSettings = [
            'type' => 'setting',
            'data' => ['FormBuilder' => $this->getRenderInfo()],
        ];

        return [
            '#prefix'   => !empty($_GET['debug']) ? kpr($jsSettings['data']['FormBuilder'], true) : '',
            '#markup'   => theme_render_template(
                $this->template, [
                'data' => $jsSettings['data']['FormBuilder']
            ]),
            '#attached' => [
                'css' => [
                    drupal_get_path('module', 'form_builder') . '/css/entity.editing.css'
                ],
                'js'  => array_merge(
                    array_map(function($path) {
                        return ['type' => 'external', 'data' => $path];
                    }, $this->externalJS), [
                    $jsSettings,
                    drupal_get_path('module', 'form_builder') . '/js/builder/pages.js',
                    drupal_get_path('module', 'form_builder') . '/js/builder/groups.js',
                    drupal_get_path('module', 'form_builder') . '/js/builder/fields.js',
                    drupal_get_path('module', 'form_builder') . '/js/builder/types.js',
                    drupal_get_path('module', 'form_builder') . '/js/builder/form.js',
                    drupal_get_path('module', 'form_builder') . '/js/builder/app.js'
                ])
            ]
        ];
    }

    private function getRenderInfo()
    {
        return [
            'modulePath' => drupal_get_path('module', 'form_builder'),
            'available'  => $this->getAvailableInfo(),
            'entity'     => $this->getEntityInfo(),
        ];
    }

    private function getAvailableInfo()
    {
        $convertor = new FormEntityToArray();
        return [
            'languages'   => language_list('enabled')[1],
            'entityTypes' => $convertor->convertEntityTypes(form_builder_manager()->getEntityTypes()),
        ];
    }

    private function getEntityInfo()
    {
        return (new FormEntityToArray())->convertForm($this->ctrl->entity);
    }

}
