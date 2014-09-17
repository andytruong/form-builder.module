<?php

namespace Drupal\form_builder\Controller\EntityViewController;

use Drupal\form_builder\FormEntity;
use Drupal\form_builder\Helper\ArrayToFormCenterEntity;
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
        if ($this->validateToken($token) && $submission = $this->createFormSubmission($request)) {
            switch ($action) {
                case 'next':
                    return $this->handleNext($submission, $pageNumber);
                case 'back':
                    return $this->handleBack($submission, $pageNumber);
                case 'submit':
                    return $this->handleSubmit($submission, $pageNumber);
                default:
                    throw new UnexpectedValueException('Wrong form action.');
            }
        }
    }

    private function createFormSubmission(array $request)
    {
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

    private function handleSubmit(FormSubmissionInterface $submission, $pageNumber)
    {
        // @TODO: Remove debug code
        if (true || $this->form->getLayoutOptions()->isLastPage($pageNumber)) {
            foreach ($submission->getEntities() as $entityTypeName => $entity) {
                $storageHandler = form_builder_manager()->getEntityStorageHandler($entityTypeName);
                $storageHandler->create($entity);
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
