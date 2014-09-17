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
        $submission = form_builder_manager()->createFormSubmission($this->form);
        unset($request['form-token'], $request['form-page'], $request['form-action']);

        if (!$this->validateToken($token)) {
            throw new RuntimeException('Invalid token');
        }

        switch ($action) {
            case 'next':
                return $this->handleNext($submission, $request, $pageNumber);

            case 'back':
                return $this->handleBack($submission, $request, $pageNumber);

            case 'submit':
                return $this->handleSubmit($submission, $request, $pageNumber);

            default:
                throw new UnexpectedValueException('Wrong form action.');
        }
    }

    private function validateToken($token)
    {
        return $token === $token; // @TODO: do later
    }

    private function handleNext(FormSubmissionInterface $submission, array $request, $pageNumber)
    {
        
    }

    private function handleBack(FormSubmissionInterface $submission, array $request, $pageNumber)
    {

    }

    private function handleSubmit(FormSubmissionInterface $submission, array $request, $pageNumber)
    {
        $convertor = new ArrayToFormCenterEntity();
        foreach ($this->form->getEntityTypes() as $entityTypeName => $entityType) {
            $entityRequest = $request[str_replace('.', '_', $entityType->getName())];
            $entity = $convertor->convert($entityType, $entityRequest);
            $submission->setEntity($entityTypeName, $entity);
        }

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
