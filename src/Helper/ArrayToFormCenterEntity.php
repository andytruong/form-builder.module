<?php

namespace Drupal\form_builder\Helper;

use DateTime;
use GO1\FormCenter\Entity\EntityBase;
use GO1\FormCenter\Entity\EntityInterface;
use GO1\FormCenter\Entity\Type\EntityTypeInterface;
use GO1\FormCenter\Field\FieldValueItem;
use Symfony\Component\Validator\Exception\RuntimeException;

class ArrayToFormCenterEntity {

  public function convert(EntityTypeInterface $entityType, $array) {
    $entity = new EntityBase();
    $entity->setEntityType($entityType);
    foreach ($array as $fieldName => $fieldValue) {
      $this->convertField($entity, $fieldName, $fieldValue);
    }
    return $entity;
  }

  private function convertField(EntityInterface $entity, $fieldName, $fieldValue) {
    if (!$entity->getEntityType()->getField($fieldName)) {
      throw new RuntimeException(strtr('!fieldName is not a valid field of !typeName', [
          '!fieldName' => $fieldName,
          '!typeName'  => $entity->getEntityType()->getName()
      ]));
    }

    // this is not a Drupal entity field, but entity property
    if (is_scalar($fieldValue) || !isset($fieldValue['und'][0])) {
      // Convert input by date widget to timestamp format.
      if (isset($fieldValue['year']) && isset($fieldValue['month']) && isset($fieldValue['year'])) {
        $date = new DateTime();
        $date->setDate($fieldValue['year'], $fieldValue['month'], $fieldValue['day']);
        $fieldValue = $date->getTimestamp();
      }

      $entity->setFieldValueItem($fieldName, new FieldValueItem(['value' => $fieldValue]));
      return;
    }

    foreach ($fieldValue['und'] as $delta => $fieldValueItem) {
      $entity->setFieldValueItem($fieldName, new FieldValueItem($fieldValueItem), $delta);
    }
  }

}
