<?php

namespace Drupal\form_builder\Helper;

use AndyTruong\Serializer\Event;
use AndyTruong\Serializer\Unserializer;
use Drupal\form_builder\Controller\FormEntityController;
use Drupal\form_builder\FormEntity;

class ArrayToFormEntity
{

    private $inArray;

    public function __construct(array $inArray)
    {
        $this->inArray = $inArray;
    }

    /**
     * @return FormEntity
     */
    public function convert()
    {
        $unserializer = new Unserializer();
        $unserializer
            ->getDispatcher()
            ->addListener('unserialize.array.before', [$this, 'onUnserializeBefore']);
        $unserializer
            ->getDispatcher()
            ->addListener('unserialize.array.after', [$this, 'onUnserializeAfter']);
        return $unserializer->fromArray($this->inArray, 'Drupal\form_builder\FormEntity');
    }

    public function onUnserializeBefore(Event $event)
    {
        $inArray = $event->getInArray();
        unset($inArray['entityTypes'], $inArray['fields'], $inArray['layoutOptions'], $inArray['listeners'], $inArray['uuid_generator']);
        $event->setInArray($inArray);
    }

    public function onUnserializeAfter(Event $event)
    {
        /* @var $form FormEntity */
        /* @var $ctrl FormEntityController */
        $form = $event->getOutObject();
        $inArray = $this->inArray;

        // Convert entity types
        if (!empty($inArray['entityTypes'])) {
            $inArray['entity_types'] = [];
            foreach ($inArray['entityTypes'] as $entityTypeName => $selected) {
                if ($selected) {
                    $inArray['entity_types'][] = $entityTypeName;
                }
            }
            unset($inArray['entityTypes']);
        }

        // Convert layout option -> field weights
        if (!empty($inArray['fields'])) {
            $pageUuid = 'page-one'; // @TODO: Remove hardcode, a form can have multiple pages
            $inArray['layout_options'] = [$pageUuid => []];
            foreach ($inArray['fields'] as $fieldUuid => $fieldArray) {
                $inArray['layout_options'][$pageUuid]['fields'][$fieldUuid]['weight'] = $fieldArray['weight'];
            }
        }

        // Convert fields
        if (!empty($inArray['fields'])) {
            $inArray['form_fields'] = [];
            foreach ($inArray['fields'] as $fieldUuid => $fieldArray) {
                $inArray['form_fields'][$fieldArray['entityTypeName']][$fieldArray['name']] = $fieldUuid;
            }
            unset($inArray['fields']);
        }

        foreach (['entity_types', 'form_fields', 'layout_options', 'form_listeners'] as $key) {
            if (!empty($inArray[$key])) {
                $form->{$key} = $inArray[$key];
            }
        }

        (new FormEntityFixer())->fix($form);
    }

}
