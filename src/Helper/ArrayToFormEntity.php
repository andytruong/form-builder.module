<?php

namespace Drupal\form_builder\Helper;

use AndyTruong\Serializer\Event;
use AndyTruong\Serializer\Unserializer;
use Drupal\form_builder\Controller\FormEntityController;
use Drupal\form_builder\FormEntity;

class ArrayToFormEntity
{

    private $inArray;

    public function __construct(array $array)
    {
        $this->inArray = $array;
    }

    public function convert()
    {
        $unserializer = new Unserializer();
        $unserializer
            ->getDispatcher()
            ->addListener('unserialize.array.before', array($this, 'onUnserializeBefore'));
        $unserializer
            ->getDispatcher()
            ->addListener('unserialize.array.after', array($this, 'onUnserializeAfter'));
        return $unserializer->fromArray($this->inArray, 'Drupal\form_builder\FormEntity');
    }

    public function onUnserializeBefore(Event $event)
    {
        $inArray = $event->getInArray();
        unset($inArray['entityTypes'], $inArray['fields'], $inArray['listeners'], $inArray['uuid_generator']);
        $event->setInArray($inArray);
    }

    public function onUnserializeAfter(Event $event)
    {
        /* @var $form FormEntity */
        /* @var $ctrl FormEntityController */
        $form = $event->getOutObject();
        $inArray = $this->inArray;

        if (!empty($inArray['entityTypes'])) {
            $inArray['entity_types'] = array_keys($inArray['entityTypes']);
            unset($inArray['entityTypes']);
        }

        foreach (array('entity_types', 'form_fields', 'layout_options', 'form_listeners') as $key) {
            if (!empty($inArray[$key])) {
                $form->{$key} = $inArray[$key];
            }
        }

        $ctrl = entity_get_controller('form_builder_form');
        $ctrl->fixEntity($form, $debug = true);
    }

}
