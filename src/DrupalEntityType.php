<?php

namespace Drupal\form_builder;

use GO1\FormCenter\Entity\Type\EntityTypeBase;

class DrupalEntityType extends EntityTypeBase
{

    protected function getDefaultStorageHandler()
    {
        return new DrupalEntityStorageHandler();
    }

}
