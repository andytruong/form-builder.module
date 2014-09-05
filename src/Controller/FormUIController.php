<?php

namespace Drupal\form_builder\Controller;

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

        return $items;
    }

}
