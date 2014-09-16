<?php

namespace Drupal\form_builder\FormCenter;

use GO1\FormCenter\Entity\EntityInterface;
use GO1\FormCenter\Entity\Storage\EntityStorageHandlerInterface;

class DrupalEntityStorageHandler implements EntityStorageHandlerInterface
{

    use \AndyTruong\Common\Traits\NameAwareTrait;

    public function __construct()
    {
        $this->setName('drupal');
        $this->setHumanName('Drupal storage handler');
    }

    public function support($entityTypeName)
    {
        return strpos($entityTypeName, 'drupal.');
    }

    public function create(EntityInterface $entity)
    {
        ;
    }

    public function delete(EntityInterface $entity)
    {
        ;
    }

    public function deleteById($entityTypeName, $id)
    {

    }

    /**
     * {@inheritdoc}
     * @param EntityInterface $entity
     * @param array $cmds
     */
    public function patch(EntityInterface $entity, array $cmds = array())
    {
        ;
    }

    /**
     * {@inheritdoc}
     * @param EntityInterface $entity
     */
    public function update(EntityInterface $entity)
    {
        ;
    }

}
