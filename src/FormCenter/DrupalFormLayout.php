<?php

namespace Drupal\form_builder\FormCenter;

use AndyTruong\Serializer\Serializer;
use Drupal\form_builder\FormEntity;
use Drupal\form_builder\Helper\FormTokenHelper;
use GO1\FormCenter\Form\FormInterface;
use GO1\FormCenter\Form\Layout\FieldGroup;
use GO1\FormCenter\Form\Layout\FormLayoutHTML;

class DrupalFormLayout extends FormLayoutHTML
{

    public function __construct()
    {
        $this->setName('html');
        $this->setHumanName('HTML Layout');
        $this->setTemplateEngine(form_builder_manager()->getTemplateEngine());
    }

    public function getToken(FormInterface $form, $pageNumber = 1)
    {
        return (new FormTokenHelper())->generate($form, $pageNumber);
    }

    /**
     * @param FormEntity $form
     * @param int $pageNumber
     */
    public function getAction(FormInterface $form, $pageNumber = 1)
    {
        return url($form->getPath($pageNumber));
    }

    /**
     * @param FormEntity $form
     * @param string|int $pageNumber
     * @return string
     */
    public function getPager(FormInterface $form, $pageNumber)
    {
//        $items = [];
//        foreach ($form->getLayoutOptions()->getPages() as $pageUuid => $pageInfo) {
//            $item['id'] = 'page-' . $pageUuid;
//            $item['data'] = l($pageInfo['title'], $form->getPath($pageNumber));
//            if (!empty($pageInfo['description'])) {
//                $item['data'] .= '<div class="description">' . $pageInfo['description'] . '</div>';
//            }
//            $items[] = $item;
//        }
//        return theme('item_list', ['items' => $items, 'attributes' => ['class' => 'form-pager']]);
    }

    public function getFormButons(FormInterface $form, $pageNumber = 1)
    {
        $element['#parents'] = [];

        if ($form->getLayoutOptions()->isLastPage($pageNumber)) {
            $element['submit'] = [
                '#prefix' => '<button name="form-action" value="submit">',
                '#suffix' => '</button>',
                '#markup' => t('Submit')
            ];
        }

        if ($form->getLayoutOptions()->getPreviousPage($pageNumber)) {
            $element['back'] = [
                '#prefix' => '<button name="form-action" value="back">',
                '#suffix' => '</button>',
                '#markup' => t('Back')
            ];
        }

        if ($form->getLayoutOptions()->getNextPage($pageNumber)) {
            $element['next'] = [
                '#prefix' => '<button name="form-action" value="next">',
                '#suffix' => '</button>',
                '#markup' => t('Next')
            ];
        }

        $form_state = ['values' => []];
        form_builder('form_builder_element', $element, $form_state);

        return drupal_render($element);
    }

    protected function doRender($params)
    {
        foreach ($params['groups'] as $groupId => $groupInfo) {
            $element = [
                '#title'       => $groupInfo['title'],
                '#description' => $groupInfo['description'],
                '#collapsible' => isset($groupInfo['options']['collapsible']) ? $groupInfo['options']['collapsible'] : true,
                '#collapsed'   => isset($groupInfo['options']['collapsed']) ? $groupInfo['options']['collapsed'] : false,
            ];

            if (!empty($params['positions'][$groupId])) {
                foreach ($params['positions'][$groupId] as $child) {
                    $id = $child['id'];
                    $element['#value'][$id] = [
                        '#markup' => $params['fields'][$id],
                        '#weight' => $child['weight'],
                    ];
                    unset($params['fields'][$id]);
                }
                $element['#value'] = drupal_render($element['#value']);
            }

            $params['fields'][$groupId] = theme('fieldset', ['element' => $element]);
        }

        return parent::doRender($params);
    }

}
