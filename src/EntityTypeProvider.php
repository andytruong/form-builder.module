<?php

namespace Drupal\form_builder;

use GO1\FormCenter\Entity\Provider\EntityTypeProviderBase;
use GO1\FormCenter\Entity\Type\EntityTypeInterface;

class EntityTypeProvider extends EntityTypeProviderBase
{

    public function __construct()
    {
        $this->setName('drupal.provider.entity_type');
        $this->setHumanName('[Drupal] Entity type provider');
    }

    /**
     * {@inheritdoc}
     * @return EntityTypeInterface[]
     */
    public function discoverEntityTypes()
    {
        $entityTypes = [];
        foreach (entity_get_info() as $entityName => $entityInfo) {
            $entityType = new DrupalEntityType();
            $entityType->setName($entityName);
            $entityType->setHumanName($entityInfo['label']);
            $entityType->setIDKey($entityInfo['entity keys']['id']);
            $entityTypes[$entityName] = $entityType;
        }
        return $entityTypes;
    }

}
