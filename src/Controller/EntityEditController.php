<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\Controller\EntityEditController\Render;
use Drupal\form_builder\Controller\EntityEditController\SubmitHandler;
use Drupal\form_builder\FormEntity;

/**
 * TODO:
 *
 * 1. User add/remove entity type => update fields.
 * 2. Available fields list
 * 3. Added fields list
 * 4. …
 * n. Submit handler
 *
 */
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

    public function getSubmitHandler()
    {
        return new SubmitHandler($this);
    }

    public static function pageCallback($entityType, $entity)
    {
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
