<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\Controller\EntityEditController\Render;
use Drupal\form_builder\Controller\EntityEditController\SubmitHandler;
use Drupal\form_builder\FormEntity;

class EntityEditController
{

    public $entity;

    public function __construct(FormEntity $entity)
    {
        $this->entity = $entity;
    }

    public function getRender()
    {
        return new Render($this);
    }

    private function getRenderInfo()
    {
        return array(
            'available' => $this->getAvailableInfo(),
            'entity'    => $this->getEntityInfo(),
        );
    }

    public static function pageCallback($entityType, $entity)
    {
        $me = new self($entity);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return $me->getRender()->render($entityType);

            case 'POST':
                return $me->getSubmitHandler()->handle(json_decode(file_get_contents('php://input'), true));
        }
    }

}
