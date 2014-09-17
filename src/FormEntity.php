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
    public $uid;

    /** @var bool */
    public $status = true;

    /** @var string */
    public $language;

    /** @var string * */
    public $slug;

    public function __construct(array $values = [])
    {
        parent::__construct($values, 'form_builder_form');
    }

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

    function getSlug()
    {
        return $this->slug;
    }

    function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getPath($pageNumber)
    {
        return "form/{$this->fid}/{$this->getSlug()}/{$pageNumber}";
    }

}
