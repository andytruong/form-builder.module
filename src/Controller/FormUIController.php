<?php

namespace Drupal\form_builder\Controller;

use EntityDefaultUIController;

class FormUIController extends EntityDefaultUIController
{

    public function hook_menu()
    {
        $items = parent::hook_menu();

        $items['admin/structure/fob-form']['access callback'] = 'user_access';
        $items['admin/structure/fob-form']['access arguments'] = array('administer forms');

        return $items;
    }

}
