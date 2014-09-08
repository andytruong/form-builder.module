<?php

namespace Drupal\form_builder;

use Entity;
use GO1\FormCenter\Form\FormInterface;

class FormEntity extends Entity implements FormInterface
{

    use \GO1\FormCenter\Form\FormTrait,
        \AndyTruong\Uuid\UuidGeneratorAwareTrait,
        \AndyTruong\Serializer\SerializableTrait;

    /** @var int */
    public $fid;

    /** @var string Name */
    public $title;

    /** @var integer The user id of the profile owner. */
    private $uid;

    /** @var bool */
    private $status = true;

    /** @var string */
    private $language;

    function getTitle()
    {
        return $this->title;
    }

    function getUid()
    {
        return $this->uid;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    function setUid($uid)
    {
        $this->uid = $uid;
    }

    function getStatus()
    {
        return (bool) $this->status;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    function getLanguage()
    {
        return $this->language;
    }

    function setLanguage($language)
    {
        $this->language = $language;
    }

}
