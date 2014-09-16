<?php

namespace Drupal\form_builder\Helper;

use Drupal\form_builder\FormCenter\DrupalField;
use EntityDrupalWrapper;
use EntityStructureWrapper;
use GO1\FormCenter\Entity\EntityInterface;
use GO1\FormCenter\Field\FieldValueItemInterface;
use RuntimeException;

class FormCenterEntityToDrupalEntity
{

    /**
     * @param EntityInterface $entity
     * @return EntityDrupalWrapper
     */
    public function convert(EntityInterface $entity)
    {
        $entityType = $entity->getEntityType();

        list(, $drupalEntityType, $drupalBundleName) = explode('.', $entityType->getName());
        $drupalEntity = entity_create($drupalEntityType, []);
        $drupalEntityWrapper = entity_metadata_wrapper($drupalEntityType, $drupalEntity, ['bundle' => $drupalBundleName]);
        if ($bundleKey = $drupalEntityWrapper->entityKey('bundle')) {
            $drupalEntityWrapper->{$bundleKey}->set($drupalBundleName);
        }

        foreach ($entityType->getFields() as $fieldName => $field) {
            foreach ($entity->getFieldValueItems($fieldName) as $fieldValueItem) {
                $this->convertToDrupalField($drupalEntityWrapper, $fieldName, $field, $fieldValueItem);
            }
        }

        return $drupalEntityWrapper;
    }

    public function convertToDrupalField(EntityStructureWrapper $drupalEntityWrapper, $fieldName, DrupalField $field, FieldValueItemInterface $fieldValueItem)
    {
        if (!$drupalPropertyInfo = $drupalEntityWrapper->getPropertyInfo($fieldName)) {
            throw new RuntimeException(strtr('Entity !entityType does not support property !ptyName.', [
                'entityType' => $drupalEntityWrapper->type(),
                '!ptyName'   => $fieldName]
            ));
        }

        if (isset($drupalPropertyInfo['property info'])) {
            $itemValue = [];
            foreach (array_keys($drupalPropertyInfo['property info']) as $vKey) {
                $itemValue[$vKey] = $fieldValueItem[$vKey];
            }
            $drupalEntityWrapper->{$fieldName}->set($itemValue);
        }
        else {
            $drupalEntityWrapper->{$fieldName}->set($fieldValueItem['value']);
        }
    }

}
