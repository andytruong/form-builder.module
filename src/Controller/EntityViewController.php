<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\Controller\EntityViewController\Submit;
use Drupal\form_builder\FormEntity;

class EntityViewController
{

    public static function pageCallback(FormEntity $form)
    {
        if (!$page = filter_input(INPUT_POST, 'form-page')) {
            $page = 'master';
        }

        return 'POST' === $_SERVER['REQUEST_METHOD'] // check request method
            ? (new Submit($form, $page))->handle($_POST) // submit
            : $form->render($page); // render form
    }

}
