<?php

namespace Drupal\form_builder;

use Entity;

class FormEntity extends Entity
{

    use \GO1\FormCenter\Form\FormTrait;

    /** @var int */
    public $fid;

    /**
     * @var string Name
     */
    public $title;

    /**
     * @var integer The user id of the profile owner.
     */
    private $uid;

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

}
