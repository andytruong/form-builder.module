<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\FormEntity;

class EntityViewController
{

    private $form;
    private $page;

    public function __construct(\Drupal\form_builder\FormEntity $form, $page)
    {
        $this->form = $form;
        $this->page = $page;
    }

    public static function pageCallback(FormEntity $form)
    {
        if (!$page = filter_input(INPUT_GET, 'page')) {
            $page = 'master';
        }

        return (new static($form, $page))->render();
    }

    public function render()
    {
        return $this->form->render($this->page);
    }

}
