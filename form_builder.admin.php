<?php

use Drupal\form_builder\FormEntity;

/**
 * Define structure for form entity create/edit form.
 *
 * @param array $form
 * @param array $form_state
 * @param FormEntity $entity
 * @param string $op
 * @param string $entity_type
 * @return array
 */
function form_builder_form_form($form, &$form_state, FormEntity $entity, $op = 'edit', $entity_type = null)
{
    $form['form_title'] = array(
        '#type'          => 'textfield',
        '#title'         => t('Summary'),
        '#required'      => true,
        '#default_value' => $entity->getTitle(),
    );

    $form['status'] = array(
        '#type'          => 'checkbox',
        '#title'         => t('Status'),
        '#description'   => 'Enable or disable form submission.',
        '#default_value' => $entity->getStatus(),
    );

    // Language
    $languages = array_map(function($language) {
        return $language->name;
    }, language_list('enabled')[1]);
    $form['language'] = array(
        '#type'          => (count($languages) <= 5 ? 'radios' : 'select'),
        '#title'         => t('Language'),
        '#default_value' => $entity->getLanguage(),
        '#options'       => $languages,
    );

    $form['entity_types'] = array(
        '#type'             => 'textfield',
        '#title'            => t('Entity types'),
        '#description'      => 'Set of supported (form center) entity types.',
        '#required'         => true,
        '#element_validate' => 'form_builder_valdiate_json_input',
    );

    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
        '#type'   => 'submit',
        '#value'  => t('Save form'),
        '#weight' => 40,
    );

    return $form;
}

function form_builder_valdiate_json_input()
{

}
