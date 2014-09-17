<?php

namespace Drupal\form_builder\FormCenter;

use Drupal\form_builder\FormEntity;
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

    /**
     * @param FormEntity $form
     * @param string|int $pageNumber
     * @return string
     */
    public function getPager(FormInterface $form, $pageNumber)
    {
        $items = [];
        foreach ($form->getLayoutOptions()->getPages() as $pageUuid => $pageInfo) {
            $item['id'] = 'page-' . $pageUuid;
            $item['data'] = l($pageInfo['title'], $form->getPath($pageNumber));
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
                '#name'    => 'form-action',
                '#type'    => 'submit',
                '#parents' => [],
                '#value'   => t('Submit'),
            ],
        ];

        $form_state = ['values' => []];
        form_builder('form_builder_element', $e, $form_state);

        return drupal_render($e);
    }

    public function getAction(FormInterface $form)
    {
        return url("form/" . $form->fid);
    }

}
