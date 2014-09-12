<?php

namespace Drupal\form_builder;

use GO1\FormCenter\Entity\EntityInterface;
use GO1\FormCenter\Entity\Storage\EntityStorageHandlerBase;

class DrupalEntityStorageHandler extends EntityStorageHandlerBase
{

    /** @var string */
    protected $name = 'drupal';

    /** @var string */
    protected $humanName = 'Drupal storage handler';

    public function create(EntityInterface $entity)
    {

    }

    public function delete(EntityInterface $entity)
    {

    }

    public function deleteById($entityTypeName, $id)
    {

    }

    /**
     * {@inheritdoc}
     * @param EntityInterface $entity
     * @param array $cmds
     */
    public function patch(EntityInterface $entity, array $cmds = [])
    {

    }

    /**
     * {@inheritdoc}
     * @param EntityInterface $entity
     */
    public function update(EntityInterface $entity)
    {

    }

}
