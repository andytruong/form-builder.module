<?php

namespace Drupal\form_builder\FormCenter;

use GO1\FormCenter\Field\FieldInterface;
use GO1\FormCenter\Field\FieldOptions;
use GO1\FormCenter\Field\FieldValueItemInterface;
use GO1\FormCenter\Field\Widget\FieldWidgetBase;

class DrupalFieldWidget extends FieldWidgetBase
{

    private $drupalFieldInfo;

    public function __construct(array $drupalFieldInfo)
    {
        $this->setName('drupal.abstract_widget');
        $this->setHumanName('[Drupal] Abstract widget');
        $this->drupalFieldInfo = $drupalFieldInfo;
    }

    /**
     * @param FieldInterface $field
     * @param FieldOptions $fieldOptions
     * @param FieldValueItemInterface[] $fieldValueItems
     * @return string
     */
    protected function renderFieldTypes(FieldInterface $field, FieldOptions $fieldOptions, array $fieldValueItems = [])
    {
        if (!empty($this->drupalFieldInfo['field'])) {
            return $this->renderDrupalField($field, $fieldOptions, $fieldValueItems);
        }
        return $this->renderDrupalProperty($field, $fieldOptions, $fieldValueItems);
    }

    protected function renderDrupalField(FieldInterface $field, FieldOptions $fieldOptions, array $fieldValueItems = [])
    {
        $form = [
            '#type'    => $this->drupalFieldInfo['type'],
            '#title'   => $this->drupalFieldInfo['label'],
            '#parents' => [],
            'und'      => [
                '#theme'       => 'field_multiple_value_form',
                '#cardinality' => $field->getNumberOfValues(),
                '#after_build' => ['field_form_element_after_build'],
            ],
        ];

        for ($delta = 0; $delta < $field->getNumberOfValues(); ++$delta) {
            foreach ($this->drupalFieldInfo['property info'] as $key => $propertyInfo) {
                $form['und'][$delta]['#parents'] = [];

                $form['und'][$delta][$key] = [
                    '#name'          => $field->getEntityType()->getName() . '[' . $field->getName() . ']' . "[und][$delta][$key]",
                    '#type'          => $propertyInfo['type'],
                    '#parents'       => [],
                    '#title'         => $propertyInfo['label'],
                    '#default_value' => isset($fieldValueItems[$delta][$key]) ? $fieldValueItems[$delta][$key] : null,
                ];

                if ('text' === $propertyInfo['type']) {
                    $form['und'][$delta][$key]['#type'] = 'textarea';
                }

                if (!empty($propertyInfo['options list'])) {
                    $form['und'][$delta][$key]['#type'] = 'select';
                    $form['und'][$delta][$key]['#options'] = $propertyInfo['options list']();
                }
            }
        }

        $form_state = ['values' => []];
        form_builder('form_builder_element', $form, $form_state);

        return drupal_render($form);
    }

    /**
     * @param FieldInterface $field
     * @param FieldOptions $fieldOptions
     * @param FieldValueItemInterface[] $fieldValueItems
     */
    protected function renderDrupalProperty(FieldInterface $field, FieldOptions $fieldOptions, array $fieldValueItems = [])
    {
        $e = [
            '#name'          => $field->getEntityType()->getName() . '[' . $field->getName() . ']',
            '#type'          => 'textfield',
            '#parents'       => [],
            '#required'      => !empty($this->drupalFieldInfo['required']),
            '#title'         => $this->drupalFieldInfo['label'],
            '#default_value' => isset($fieldValueItems[0]) ? $fieldValueItems[0]['value'] : '',
        ];
        if (isset($this->drupalFieldInfo['type'])) {
            if ('boolean' === $this->drupalFieldInfo['type']) {
                $e['#type'] = 'checkbox';
            }
        }

        $form_state = ['values' => []];
        form_builder('form_builder_element', $e, $form_state);

        return drupal_render($e);
    }

}
