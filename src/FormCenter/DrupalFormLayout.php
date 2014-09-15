<?php

namespace Drupal\form_builder\FormCenter;

use GO1\FormCenter\Form\FormInterface;
use GO1\FormCenter\Form\Layout\FormLayoutHTML;

class DrupalFormLayout extends FormLayoutHTML
{

    public function __construct()
    {
        $this->setName('html');
        $this->setHumanName('HTML Layout');
        $this->setTemplateEngine(form_builder_manager()->getTemplateEngine());
    }

    public function getPager(FormInterface $form, $pageNumber)
    {
        $items = [];
        foreach ($form->getLayoutOptions()->getPages() as $pageUuid => $pageInfo) {
            $item['id'] = 'page-' . $pageUuid;
            $item['data'] = l($pageInfo['title'], "form/{$form->fid}/{$pageUuid}");
            if (!empty($pageInfo['description'])) {
                $item['data'] .= '<div class="description">' . $pageInfo['description'] . '</div>';
            }
            $items[] = $item;
        }
        return theme('item_list', ['items' => $items, 'attributes' => ['class' => 'form-pager']]);
    }

    public function getFormButons(FormInterface $form, $pageNumber)
    {
        $e = [
            '#parents' => [],
            'submit'   => [
                '#type'    => 'submit',
                '#parents' => [],
                '#value'   => t('Save'),
            ],
        ];

        $form_state = ['values' => []];
        form_builder('form_builder_element', $e, $form_state);

        return drupal_render($e);
    }

}
