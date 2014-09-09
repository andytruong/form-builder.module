<?php

namespace Drupal\form_builder\Controller\EntityEditController;

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
        if (!isset($request['action'])) {
            $methods = 'handle' . at_camelize($request['action']);
            unset($request['action']);
            return $this->{$methods}($request);
        }

        if (!isset($request['action'])) {
            return array('status' => 'FAIL', 'error' => 'Missing action');
        }
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

    /**
     * Save form-entity.
     *
     * @param array $request
     * @return type
     */
    protected function handleSave(array $request)
    {
        return ['status' => 'OK', 'message' => 'Working…'];
    }

}
