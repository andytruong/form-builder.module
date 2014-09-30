<?php

namespace Drupal\form_builder\FormCenter;

use Drupal\form_builder\FormEntity;
use Drupal\form_builder\Helper\FormTokenHelper;
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
        $params['fields'] = $this->doRenderRecursive($params);
        return parent::doRender($params);
    }

    private function doRenderRecursive($params, $parent = null)
    {
        $fields = [];

        // sort elements
        usort($params['positions'][$parent], function($a, $b) {
            return $a['weight'] < $b['weight'] ? -1 : 1;
        });

        // Loop through elements
        foreach ($params['positions'][$parent] as $item) {
            $itemId = $item['id'];
            if (!$isGroup = isset($params['groups'][$itemId])) {
                $fields[$itemId] = $params['fields'][$itemId];
            }
            else {
                $fields[$itemId] = $this->doRenderGroup($params, $itemId, $params['groups'][$itemId]);
            }
        }

        return $fields;
    }

    private function doRenderGroup($params, $groupId, $groupInfo)
    {
        switch ($groupInfo['type']) {
            case 'vtabs':
                return $this->doRenderVerticalTabs($params, $groupId, $groupInfo);

            case 'fieldset':
            default:
                return $this->doRenderFieldset($params, $groupId, $groupInfo);
        }
    }

    private function doRenderVerticalTabs($params, $groupId, $groupInfo)
    {
        $element[$groupId] = [
            '#type'        => 'vertical_tabs',
            '#title'       => $groupInfo['title'],
            '#description' => $groupInfo['description'],
        ];

        foreach ($params['positions'][$groupId] as $item) {
            $itemId = $item['id'];
            if (isset($params['groups'][$itemId])) {
                $params['groups'][$itemId]['options']['collapsible'] = true;
                $params['groups'][$itemId]['options']['collapsed'] = true;
                $element[$groupId][$itemId] = $this->doRenderFieldset($params, $itemId, $params['groups'][$itemId], $groupId);
            }
        }

        return drupal_render($element);
    }

    private function doRenderFieldset($params, $groupId, $groupInfo, $parentGroupId = null)
    {
        $element = [
            '#title'       => $groupInfo['title'],
            '#description' => $groupInfo['description'],
            '#collapsible' => isset($groupInfo['options']['collapsible']) ? $groupInfo['options']['collapsible'] : true,
            '#collapsed'   => isset($groupInfo['options']['collapsed']) ? $groupInfo['options']['collapsed'] : false,
        ];

        if (!empty($params['positions'][$groupId])) {
            $element['#value'] = implode('', $this->doRenderRecursive($params, $groupId));
        }

        if (null !== $parentGroupId) {
            return $element + ['#type' => 'fieldset', '#group' => $parentGroupId];
        }

        return theme('fieldset', ['element' => $element]);
    }

}
