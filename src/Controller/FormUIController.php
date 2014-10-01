<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\FormEntity;
use EntityDefaultUIController;

class FormUIController extends EntityDefaultUIController
{

    public function hook_menu()
    {
        $items = parent::hook_menu();

        foreach ($items as &$item) {
            if (isset($item['access callback']) && $item['access callback'] === 'entity_access') {
                $item['access callback'] = 'form_builder_entity_access';
            }
        }

        // we use angularjs to build editing form for form-entity.
        $items['admin/structure/fob-form/manage/%entity_object']['page callback']
            = $items['admin/structure/fob-form/add']['page callback']
            = 'Drupal\form_builder\Controller\EntityEditController::pageCallback';

        return $items;
    }

    /**
     * @param array $conditions
     * @param int $id
     * @param FormEntity $entity
     * @param array $additional_cols
     * @return type
     */
    protected function overviewTableRow($conditions, $id, $entity, $additional_cols = array())
    {
        $return = parent::overviewTableRow($conditions, $id, $entity, $additional_cols);
        $return[] = l(t('View'), $entity->getPath(1));
        return $return;
    }

}
