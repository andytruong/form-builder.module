<?php

namespace Drupal\form_builder\Controller\EntityEditController;

use Drupal\form_builder\Controller\EntityEditController;
use Drupal\form_builder\Helper\ArrayToFormEntity;

class SubmitHandler
{

    /** @var EntityEditController */
    private $ctrl;

    public function __construct($ctrl)
    {
        $this->ctrl = $ctrl;
    }

    /**
     * Main endpoint to handle user's submission.
     *
     * @param array $request
     * @return array
     */
    public function handle(array $request)
    {
        if (isset($request['action'])) {
            $method = 'handle' . at_camelize(str_replace(array('-', ' '), '_', $request['action']));
            unset($request['action']);
            $return = $this->{$method}($request);
        }
        elseif (!isset($request['action'])) {
            $return = array('status' => 'FAIL', 'error' => 'Missing action');
        }

        drupal_add_http_header('Content-Type', 'application/json; charset=utf-8');
        echo drupal_json_encode($return);
    }

    /**
     * When user add/remove entity types.
     */
    protected function handleUpdateEntityTypes()
    {
        return [
            'status' => 'OK',
            'fields' => [
                'adding'   => [],
                'removing' => [],
            ],
        ];
    }

    protected function handleAddField(array $reqeuest)
    {
        $tmp = explode('.', $reqeuest['fieldName']);
        $fieldName = array_pop($tmp);
        $entityTypeName = implode('.', $tmp);
        $field = form_builder_manager()->getField($entityTypeName, $fieldName);

        $entity = (new ArrayToFormEntity($reqeuest['entity']))->convert();
        $fieldUuid = $entity->addField($entityTypeName, $field);

        return [
            'status'    => 'OK',
            'fieldUuid' => $fieldUuid,
            'field'     => [
                'name'           => $field->getName(),
                'humanName'      => $field->getHumanName(),
                'entityTypeName' => $entityTypeName,
                'fieldName'      => $fieldName,
            ],
        ];
    }

    /**
     * Save form-entity.
     *
     * @param array $request
     * @return type
     */
    protected function handleSave(array $request)
    {
        $entity = (new ArrayToFormEntity($request['entity']))->convert();
        $saved_entity = entity_save('form_entity_form', $entity);
        return ['status' => 'OK', 'message' => 'Working…', 'saved_entity' => $saved_entity];
    }

}
