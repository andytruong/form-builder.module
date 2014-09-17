<?php

namespace Drupal\form_builder\Controller\EntityViewController;

use Drupal\form_builder\FormEntity;
use GO1\FormCenter\Entity\EntityBase;
use GO1\FormCenter\Entity\Type\EntityTypeInterface;
use GO1\FormCenter\Field\FieldValueItem;
use GO1\FormCenter\Form\Submission\FormSubmissionInterface;
use RuntimeException;

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
        $page = (string) $request['form-page'];
        $action = (string) $request['form-action'];
        unset($request['form-token'], $request['form-page'], $request['form-action']);

        if (!$this->validateToken($token)) {
            throw new RuntimeException('Invalid token');
        }

        $submission = form_builder_manager()->createFormSubmission($this->form);

        switch ($action) {
            case 'submit':
            default:
                return $this->handleSubmit($submission, $request, $page);
        }
    }

    private function validateToken($token)
    {
        return $token === $token; // @TODO: do later
    }

    private function handleSubmit(FormSubmissionInterface $submission, array $request, $page)
    {
        foreach ($this->form->getEntityTypes() as $entityTypeName => $entityType) {
            $entityRequest = $request[str_replace('.', '_', $entityType->getName())];
            $entity = $this->arrayToEntity($entityType, $entityRequest);
            $submission->setEntity($entityTypeName, $entity);
        }

        // @TODO: Remove debug code
        if (true || $this->form->getLayoutOptions()->isLastPage($page)) {
            return $this->saveFormSubmission($submission);
        }

        return $this->goToNextPage($submission);
    }

    private function saveFormSubmission(FormSubmissionInterface $submission)
    {
        foreach ($submission->getEntities() as $entityTypeName => $entity) {
            $storageHandler = form_builder_manager()->getEntityStorageHandler($entityTypeName);
            $storageHandler->create($entity);
        }

        drupal_goto("form/{$this->form->fid}");
    }

    private function goToNextPage(FormSubmissionInterface $submission)
    {
        kpr($submission);
        exit;
    }

    private function arrayToEntity(EntityTypeInterface $entityType, $array)
    {
        $entity = new EntityBase();
        $entity->setEntityType($entityType);

        foreach ($array as $fieldName => $fieldValue) {
            if (!$entityType->getField($fieldName)) {
                $msg = '!fieldName is not a valid field of !typeName';
                throw new RuntimeException(strtr($msg, ['!fieldName', '!typeName'], [$fieldName, $entityType->getName()]));
            }

            if (!is_array($fieldValue) || !isset($fieldValue['und'][0])) {
                $entity->setFieldValueItem($fieldName, new FieldValueItem(['value' => $fieldValue]));
            }
            else {
                foreach ($fieldValue['und'] as $delta => $fieldValueItem) {
                    $entity->setFieldValueItem($fieldName, new FieldValueItem($fieldValueItem), $delta);
                }
            }
        }

        return $entity;
    }

}
