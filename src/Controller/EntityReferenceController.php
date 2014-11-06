<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\Helper\DrupalEntityAutocomplete;

class EntityReferenceController {

  /**
   * @param string $entityTypeName
   * @param string $columnName
   * @param string $input
   * @return array
   */
  public static function pageCallback($entityTypeName, $columnName, $input) {
    $return = (new DrupalEntityAutocomplete($entityTypeName, $columnName))->doExecute($input);
    echo drupal_json_encode($return);
  }

}
