<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\Controller\EntityViewController\Submit;
use Drupal\form_builder\FormEntity;

class EntityViewController
{

    public static function pageCallback(FormEntity $form, $slug = '', $pageNumber = 1)
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            if (filter_input(INPUT_POST, 'form-page')) {
                $pageNumber = filter_input(INPUT_POST, 'form-page');
            }
            return (new Submit($form, $pageNumber))->handle($_POST);
        }

        if ($slug !== $form->getSlug()) {
            drupal_goto($form->getPath($pageNumber));
        }

        return $form->render($pageNumber);
    }

}
