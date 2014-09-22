<?php

namespace Drupal\form_builder\Helper;

use Drupal\form_builder\FormEntity;

class FormSubmissionHelper
{

    public function convertFromRequest(FormEntity $form, array $request, $token)
    {
        $cacheId = (new FormTokenHelper())->getDrupalCacheId($token);
        $this->mergeCachedRequest($cacheId, $request);

        $submission = form_builder_manager()->createFormSubmission($form);
        $convertor = new ArrayToFormCenterEntity();
        foreach ($form->getEntityTypes() as $entityTypeName => $entityType) {
            $entityRequest = $request[str_replace('.', '_', $entityType->getName())];
            $entity = $convertor->convert($entityType, $entityRequest);
            $submission->setEntity($entityTypeName, $entity);
        }
        return $submission;
    }

    private function mergeCachedRequest($cacheId, &$request)
    {
        // no cached-request, nothing to to
        if (!$cache = cache_get($cacheId)) {
            return;
        }

        // cached request is invalid, cann not do anything
        if (!$cachedRequest = json_decode($cache->data, true)) {
            return;
        }

        $newRequest = $request;
        $request = $cachedRequest;
        foreach ($newRequest as $entityTypeName => $entityValues) {
            foreach ($entityValues as $fieldName => $fieldValueItems) {
                $request[$entityTypeName][$fieldName] = $fieldValueItems;
            }
        }

        // Cache latest request
        cache_set($cacheId, json_encode($request), 'cache', strtotime('+ 6 hours'));
    }

}
