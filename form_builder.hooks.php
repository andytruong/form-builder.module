<?php

/**
 * Implements hook_permisison().
 */
function form_builder_permission()
{
    $permissions['administer forms'] = array(
        'title'           => t('Administer forms'),
        'restrict access' => false,
    );
    return $permissions;
}

/**
 * Implements hook_menu().
 */
function form_builder_menu()
{
    $items = array();

    $items['admin/structure/fob-form'] = array(
        'title'            => 'Forms',
        'description'      => 'Manage entity forms.',
        'access arguments' => array('administer forms'),
        'page callback'    => 'Drupal\form_builder\Controller\AdminLandingController::pageCallback',
    );

    return $items;
}

/**
 * Implements hook_entity_info().
 */
function form_builder_entity_info()
{
    $info = array();

    $info['fob_form'] = array(
        'label'            => t('Form'),
        'plural label'     => t('Forms'),
        'entity class'     => 'Drupal\form_builder\FormEntity',
        'controller class' => 'Drupal\form_builder\FormEntityController',
        'base table'       => 'fob_form',
        'static cache'     => true,
        'fieldable'        => false,
        'entity class'     => 'Drupal\form_builder\FormEntity',
        'entity keys'      => array(
            'id'       => 'fid',
            'label'    => 'name',
            'language' => 'language',
        ),
        'bundles'          => array(
            'fob_form' => array(
                'label' => t('Form'),
                'admin' => array(
                    'path'             => 'admin/structure/fob-form',
                    'access arguments' => array('administer forms'),
                ),
            ),
        ),
        'view modes'       => array(
            'default' => array(
                'label'           => t('Default'),
                'custom settings' => false,
            ),
        ),
    );

    return $info;
}

/**
 * Implements hook_entity_propety_info().
 */
function form_builder_entity_property_info()
{
    $info = array('fob_form' => array('properties' => array()));
    $pties = &$info['fob_form']['properties'];

    $pties['language'] = array(
        'label'             => t("Language"),
        'type'              => 'token',
        'description'       => t("The language the node is written in."),
        'setter callback'   => 'entity_metadata_verbatim_set',
        'options list'      => 'entity_metadata_language_list',
        'schema field'      => 'language',
        'setter permission' => 'administer forms',
    );

    $pties['author'] = array(
        'label'             => t("Author"),
        'type'              => 'user',
        'description'       => t("The author of the form."),
        'getter callback'   => 'entity_metadata_node_get_properties',
        'setter callback'   => 'entity_metadata_node_set_properties',
        'setter permission' => 'administer forms',
        'required'          => true,
        'schema field'      => 'uid',
    );

    return $info;
}
