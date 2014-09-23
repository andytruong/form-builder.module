<?php

namespace Drupal\form_builder\Controller\EntityViewController;

use Drupal\form_builder\FormEntity;
use Drupal\form_builder\Helper\FormSubmissionHelper;
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

        if (!$this->validateToken($token)) {
            throw new RuntimeException('Invalid token.');
        }

        if (!$submission = (new FormSubmissionHelper())->convertFromRequest($this->form, $request, $token)) {
            throw new RuntimeException('Invalid form submission.');
        }

        if (($action != 'back') && ($errors = $submission->validate($pageNumber)) && count($errors)) {
            return $errors;
        }

        switch ($action) {
            case 'next':
                return $this->handleNext($submission, $pageNumber, $token);
            case 'back':
                return $this->handleBack($submission, $pageNumber, $token);
            case 'submit':
                return $this->handleSubmit($submission, $token);
            default:
                throw new UnexpectedValueException('Wrong form action.');
        }
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
