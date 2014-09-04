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
    private $name;

    /**
     * @var integer The user id of the profile owner.
     */
    private $uid;

    function getName()
    {
        return $this->name;
    }

    function getUid()
    {
        return $this->uid;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setUid($uid)
    {
        $this->uid = $uid;
    }

}
