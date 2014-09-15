<?php

/**
 * Implements hook_permisison().
 */
function form_builder_permission()
{
    $permissions['administer forms'] = [
        'title'           => t('Administer forms'),
        'restrict access' => false,
    ];
    return $permissions;
}

/**
 * Implements hook_menu()
 */
function form_builder_menu()
{
    $items['form/%form_builder'] = [
        'title callback'   => 'entity_label',
        'title arguments'  => ['form_builder_form', 1],
        'access callback'  => 'form_builder_entity_access',
        'access arguments' => ['view', 'form_builder_form', 1],
        'page callback'    => 'Drupal\\form_builder\\Controller\\EntityViewController::pageCallback',
        'page arguments'   => [1],
    ];
    return $items;
}

/**
 * Implements hook_entity_info().
 */
function form_builder_entity_info()
{
    $info = [];

    $info['form_builder_form'] = [
        'label'            => t('Form'),
        'plural label'     => t('Forms'),
        'entity class'     => 'Drupal\form_builder\FormEntity',
        'controller class' => 'Drupal\form_builder\Controller\FormEntityController',
        'module'           => 'form_builder',
        'base table'       => 'fob_form',
        'static cache'     => true,
        'fieldable'        => false,
        'entity class'     => 'Drupal\form_builder\FormEntity',
        'entity keys'      => [
            'id'       => 'fid',
            'label'    => 'title',
            'language' => 'language',
        ],
        'bundles'          => [
            'form_builder_form' => [
                'label' => t('Form'),
                'admin' => [
                    'path'             => 'admin/structure/fob-form',
                    'access arguments' => ['administer forms'],
                ],
            ],
        ],
        'view modes'       => [
            'default' => [
                'label'           => t('Default'),
                'custom settings' => false,
            ],
        ],
        'admin ui'         => [
            'path'             => 'admin/structure/fob-form',
            'controller class' => 'Drupal\form_builder\Controller\FormUIController',
        ],
    ];

    return $info;
}

/**
 * Implements hook_entity_propety_info().
 */
function form_builder_entity_property_info()
{
    $info = ['fob_form' => ['properties' => []]];
    $pties = &$info['fob_form']['properties'];

    $pties['language'] = [
        'label'             => t("Language"),
        'type'              => 'token',
        'description'       => t("The language the node is written in."),
        'setter callback'   => 'entity_metadata_verbatim_set',
        'options list'      => 'entity_metadata_language_list',
        'schema field'      => 'language',
        'setter permission' => 'administer forms',
    ];

    $pties['author'] = [
        'label'             => t("Author"),
        'type'              => 'user',
        'description'       => t("The author of the form."),
        'getter callback'   => 'entity_metadata_node_get_properties',
        'setter callback'   => 'entity_metadata_node_set_properties',
        'setter permission' => 'administer forms',
        'required'          => true,
        'schema field'      => 'uid',
    ];

    return $info;
}
