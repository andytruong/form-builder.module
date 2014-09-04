<?php

/**
 * Implements hook_permisison().
 */
function form_builder_permission()
{
    $permissions['administer forms'] = array(
        'title'           => t('Administer forms'),
        'restrict access' => true,
    );
    return $permissions;
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
