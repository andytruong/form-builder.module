<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\FormEntity;
use GO1\FormCenter\Field\FieldValueItem;

class EntityViewController
{

    private $form;
    private $page;

    public function __construct(FormEntity $form, $page)
    {
        $this->form = $form;
        $this->page = $page;
    }

    public static function pageCallback(FormEntity $form)
    {
        if (!$page = filter_input(INPUT_GET, 'page')) {
            $page = 'master';
        }

        $controller = new static($form, $page);
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            return $controller->submit($_POST);
        }

        return $controller->render();
    }

    public function render()
    {
        return $this->form->render($this->page);
    }

    public function submit(array $request)
    {
        $sm = \form_builder_manager()->createFormSubmission($this->form);
        foreach ($this->form->getEntityTypes() as $entityType) {
            $entityRequest = $request[str_replace('.', '_', $entityType->getName())];
            foreach ($entityRequest as $fieldName => $fieldRequest) {
                // $fieldValue = new FieldValueItem();
            }
        }

        // $sm->setFieldInput($fieldUuid, $fieldValue, $delta);
        // $this->form->set
        kpr($request);
        exit;
    }

}
