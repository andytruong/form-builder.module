<?php

namespace Drupal\form_builder\Helper;

use EntityFieldQuery;
use SelectQuery;

class DrupalEntityAutocomplete extends EntityFieldQuery
{

    private $entityTypeName;
    private $columnName;

    public function __construct($entityTypeName, $columnName)
    {
        $this->entityTypeName = $entityTypeName;
        $this->columnName = $columnName;
    }

    public function doExecute($input)
    {
        $this->entityCondition('entity_type', $this->entityTypeName);
        $this->propertyCondition($this->columnName, $input, 'CONTAINS');
        return $this->execute();
    }

    /**
     *
     * @param SelectQuery $selectQuery
     * @param string $idKey
     */
    public function finishQuery($selectQuery, $idKey = 'entity_id')
    {
        $entityTable = reset($selectQuery->getTables());
        $selectQuery->addField($entityTable['alias'], $this->columnName);

        $return = array();
        foreach ($selectQuery->execute() as $partialEntity) {
            $value = $partialEntity->{$this->columnName} . ' [id:' . $partialEntity->{$idKey} . ']';
            $label = $partialEntity->{$this->columnName};
            $return[$value] = $label;
        }
        return $return;
    }

}
