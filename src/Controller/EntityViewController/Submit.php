<?php

namespace Drupal\form_builder\Controller\EntityViewController;

use Drupal\form_builder\FormEntity;
use Drupal\form_builder\Helper\ArrayToFormCenterEntity;
use Drupal\form_builder\Helper\FormTokenHelper;
use GO1\FormCenter\Form\Submission\FormSubmissionInterface;
use RuntimeException;
use UnexpectedValueException;

class Submit
{

    /** @var FormEntity */
    private $form;

    public function __construct(FormEntity $form)
    {
        $this->form = $form;
    }

    public function handle(array $request)
    {
        // setup variables
        $token = (string) $request['form-token'];
        $pageNumber = (string) $request['form-page'];
        $action = (string) $request['form-action'];
        unset($request['form-token'], $request['form-page'], $request['form-action']);

        // wrapper flow
        if ($this->validateToken($token) && $submission = $this->createFormSubmission($request, $token)) {
            switch ($action) {
                case 'next':
                    return $this->handleNext($submission, $pageNumber, $token);
                case 'back':
                    return $this->handleBack($submission, $pageNumber, $token);
                case 'submit':
                    return $this->handleSubmit($submission, $pageNumber, $token);
                default:
                    throw new UnexpectedValueException('Wrong form action.');
            }
        }
    }

    private function createFormSubmission(array $request, $token)
    {
        $cacheId = (new FormTokenHelper())->getDrupalCacheId($token);

        // Check cached data, merge them with new request
        if ($cache = cache_get($cacheId)) {
            $newRequest = $request;

            if ($cachedRequest = json_decode($cache->data, true)) {
                $request = $cachedRequest;

                foreach ($newRequest as $entityTypeName => $entityValues) {
                    foreach ($entityValues as $fieldName => $fieldValueItems) {
                        $request[$entityTypeName][$fieldName] = $fieldValueItems;
                    }
                }
            }
        }

        // Cache latest request
        cache_set($cacheId, json_encode($request), 'cache', strtotime('+ 6 hours'));

        dsm($request);

        $submission = form_builder_manager()->createFormSubmission($this->form);
        $convertor = new ArrayToFormCenterEntity();
        foreach ($this->form->getEntityTypes() as $entityTypeName => $entityType) {
            $entityRequest = $request[str_replace('.', '_', $entityType->getName())];
            $entity = $convertor->convert($entityType, $entityRequest);
            $submission->setEntity($entityTypeName, $entity);
        }
        return $submission;
    }

    private function validateToken($token)
    {
        if ($token !== $token) { // @TODO: do later
            throw new RuntimeException('Invalid token');
        }
        return true;
    }

    private function handleNext(FormSubmissionInterface $submission, $pageNumber)
    {
        $nextPageNumber = $this->form->getLayoutOptions()->getNextPage($pageNumber);
        $path = $this->form->getPath($nextPageNumber);
        drupal_goto($path);
    }

    private function handleBack(FormSubmissionInterface $submission, $pageNumber)
    {
        $previousPageNumber = $this->form->getLayoutOptions()->getPreviousPage($pageNumber);
        $path = $this->form->getPath($previousPageNumber);
        drupal_goto($path);
    }

    private function handleSubmit(FormSubmissionInterface $submission, $pageNumber, $token)
    {
        if ($this->form->getLayoutOptions()->isLastPage($pageNumber)) {
            foreach ($submission->getEntities() as $entityTypeName => $entity) {
                $storageHandler = form_builder_manager()->getEntityStorageHandler($entityTypeName);
                $storageHandler->create($entity);
            }

            if ($cacheId = (new FormTokenHelper())->getDrupalCacheId($token)) {
                cache_clear_all($cacheId, 'cache');
            }

            if ($msg = $this->form->getLayoutOptions()->getConfirmationMessage()) {
                drupal_set_message($msg);
            }

            drupal_goto("form/{$this->form->fid}");
        }

        return $this->goToNextPage($submission);
    }

    private function goToNextPage(FormSubmissionInterface $submission)
    {
        kpr($submission);
        exit;
    }

}
