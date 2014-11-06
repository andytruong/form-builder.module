<?php

namespace Drupal\form_builder\Controller\EntityEditController;

use Drupal\form_builder\Controller\EntityEditController;
use Drupal\form_builder\Helper\FormEntityToArray;

class Render {

  /** @var EntityEditController */
  private $ctrl;

  /** @var string */
  public $template;

  public function __construct($ctrl) {
    $this->ctrl = $ctrl;
    $this->template = drupal_get_path('module', 'form_builder') . '/templates/form.edit.tpl.php';
  }

  public function render() {
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
            'library' => [['form_builder', 'form_builder.application']],
            'js'      => [
                $jsSettings
            ]
        ]
    ];
  }

  private function getRenderInfo() {
    return [
        'modulePath' => drupal_get_path('module', 'form_builder'),
        'available'  => $this->getAvailableInfo(),
        'entity'     => $this->getEntityInfo(),
    ];
  }

  private function getAvailableInfo() {
    $convertor = new FormEntityToArray();
    return [
        'languages'   => language_list('enabled')[1],
        'entityTypes' => $convertor->convertEntityTypes(form_builder_manager()->getEntityTypes()),
    ];
  }

  private function getEntityInfo() {
    return (new FormEntityToArray())->convertForm($this->ctrl->entity);
  }

}
