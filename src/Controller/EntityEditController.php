<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\Controller\EntityEditController\Render;
use Drupal\form_builder\Controller\EntityEditController\SubmitHandler;
use Drupal\form_builder\FormEntity;

class EntityEditController {

  /** @var FormEntity */
  public $entity;

  public function __construct(FormEntity $entity = null) {
    if (null === $entity) {
      $entity = form_builder_manager()->createForm();
    }
    $this->entity = $entity;
  }

  public function getRender() {
    return new Render($this);
  }

  public function getSubmitHandler() {
    return new SubmitHandler($this);
  }

  /**
   * @param string $entityType
   * @param FormEntity $entity
   * @return type
   */
  public static function pageCallback($entityType, $entity = null) {
    $me = new self($entity);

    switch ($_SERVER['REQUEST_METHOD']) {
      case 'GET':
        return $me->getRender()->render($entityType);

      case 'POST':
        $request = json_decode(file_get_contents('php://input'), true);
        return $me->getSubmitHandler()->handle($request);
    }
  }

}
