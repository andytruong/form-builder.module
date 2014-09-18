<?php

namespace Drupal\form_builder\Controller;

use Drupal\form_builder\Controller\EntityViewController\Submit;
use Drupal\form_builder\FormEntity;
use Drupal\form_builder\Helper\ArrayToFormCenterEntity;
use Drupal\form_builder\Helper\FormTokenHelper;

class EntityViewController
{

    public static function pageCallback(FormEntity $form, $slug = '', $pageNumber = 1)
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            if (filter_input(INPUT_POST, 'form-page')) {
                $pageNumber = filter_input(INPUT_POST, 'form-page');
            }
            return (new Submit($form, $pageNumber))->handle($_POST);
        }

        if ($slug !== $form->getSlug()) {
            drupal_goto($form->getPath($pageNumber));
        }

        return $form->render($pageNumber, self::createFormSubmissionFromCache($form, $pageNumber));
    }

    private static function createFormSubmissionFromCache(FormEntity $form, $pageNumber)
    {
        $token = (new FormTokenHelper())->generate($form, $pageNumber);
        $cacheId = (new FormTokenHelper())->getDrupalCacheId($token);

        // Check cached data, merge them with new request
        if ((!$cache = cache_get($cacheId)) || (!$request = json_decode($cache->data, true))) {
            return;
        }

        $submission = form_builder_manager()->createFormSubmission($form);
        $convertor = new ArrayToFormCenterEntity();

        foreach ($form->getEntityTypes() as $entityTypeName => $entityType) {
            $entityRequest = $request[str_replace('.', '_', $entityType->getName())];
            $entity = $convertor->convert($entityType, $entityRequest);
            $submission->setEntity($entityTypeName, $entity);
        }

        return $submission;
    }

}
