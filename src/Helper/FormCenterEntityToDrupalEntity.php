<?php

namespace Drupal\form_builder\Helper;

use Drupal\form_builder\FormCenter\DrupalField;
use EntityDrupalWrapper;
use EntityMetadataWrapperException;
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

        if (!isset($drupalPropertyInfo['property info'])) {
            return $this->convertToDrupalProperty($drupalEntityWrapper, $fieldName, $field, $fieldValueItem);
        }

        try {
            $itemValue = [];
            foreach (array_keys($drupalPropertyInfo['property info']) as $vKey) {
                $itemValue[$vKey] = $fieldValueItem[$vKey];
            }
            $drupalEntityWrapper->{$fieldName}->set($itemValue);
        }
        catch (EntityMetadataWrapperException $e) {

        }
    }

    /**
     * @TODO: invalid value maybe entered, how to handle it nicely? — EntityMetadataWrapperException
     */
    private function convertToDrupalProperty(EntityStructureWrapper $drupalEntityWrapper, $fieldName, DrupalField $field, FieldValueItemInterface $fieldValueItem)
    {
        $realValue = $fieldValueItem['value'];
        $drupalPropertyInfo = $drupalEntityWrapper->getPropertyInfo($fieldName);

        // No type hint, just set value directly
        if (empty($drupalPropertyInfo['type'])) {
            return $drupalEntityWrapper->{$fieldName}->set($realValue);
        }

        $propertyType = $drupalPropertyInfo['type'];
        if (0 === strpos($propertyType, 'list<')) {
            $propertyType = preg_replace('/^list<(.+)>$/', '$1', $propertyType);
        }

        // Basic data type, set directly
        switch ($propertyType) {
            case 'integer':
            case 'boolean':
            case 'token':
            case 'uri':
            case 'date':
            case 'text':
            case 'text_formatted':
            case 'struct':
                return $drupalEntityWrapper->{$fieldName}->set($realValue);
        }

        // property is an entity reference value (node.uid, taxonomy_term.vocabulary, …)
        // which need convert from `label [id:%entity_id]` to => `%entity_id`
        if (!$drupalEntityTypeInfo = entity_get_info($drupalEntityWrapper->type())) {
            throw new \UnexpectedValueException(strtr('Unknow data type for !fieldName: !dataType', [
                '!fieldName' => $field->getName(),
                '!dataType'  => $propertyType,
            ]));
        }

        if (preg_match('/\[id\:(\d+)\]$/', $fieldValueItem['value'], $matches)) {
            $realValue = $matches[1];
            return $drupalEntityWrapper->{$fieldName}->set($realValue);
        }

        $msg = strtr('Unexpected format for property !fieldName: `%label [id:%id]`. Given: !input', [
            '!fieldName' => $field->getName(),
            '!input'     => $fieldValueItem['value'],
        ]);
        throw new \UnexpectedValueException($msg);
    }

}
