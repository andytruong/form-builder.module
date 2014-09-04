<?php

namespace Drupal\form_builder;

use Entity;

class FormEntity extends Entity
{

    use \GO1\FormCenter\Form\FormTrait;

    /**
     * @var integer The user id of the profile owner.
     */
    public $uid;

}
