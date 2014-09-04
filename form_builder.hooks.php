<?php

/**
 * Implements hook_entity_info().
 */
function form_builder_entity_info()
{
    $info = array();

    $info['fob_form'] = array(
        'label'            => t('Form'),
        'controller class' => 'Drupal\form_builder\Entity',
        'static cache'     => true,
        'fieldable'        => false,
        'entity class'     => 'Drupal\form_builder\FormEntity',
        'entity keys'      => array(
            'id'       => 'fid',
            'label'    => 'name',
            'language' => 'language',
        ),
        'bundles'          => array(
            'form' => array(
                'label' => t('Form'),
                'admin' => array(
                    'path' => 'admin/structure/fob-form',
                ),
            ),
        ),
        'view modes'       => array(
            'full' => array(
                'label'           => t('Form Render'),
                'custom settings' => false,
            ),
        ),
    );

    return $info;
}
