<?php

namespace Novactive\Bundle\bundlezzz\Service;

use Doctrine\ORM\EntityManager;
use Novactive\Bundle\bundlezzz\Entity\Form;
use Novactive\Bundle\bundlezzz\Entity\FormSubmission;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

class FormSubmissionHelper
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * FormSubmissionHelper constructor.
     */
    public function __construct(
        EntityManager $em,
        TranslatorInterface $translator,
        RequestStack $requestStack,
        FormSubmissionFactory $formSubmissionFactory
    ) {
        $this->em                    = $em;
        $this->translator            = $translator;
        $this->request               = $requestStack->getCurrentRequest();
        $this->formSubmissionFactory = $formSubmissionFactory;
    }

    public function checkSubmissionAvailability(FormInterface $form, Form $formEntity): bool
    {
        if ($formEntity->getMaxSubmissions() > $this->getFormSubmissionCounter($formEntity)) {
            return true;
        }
        $form->addError(new FormError($this->translator->trans('novaformbuilder_error.reached_max_submissions')));

        return false;
    }

    public function createAndLogSubmission(array $data, Form $formEntity, ?int $userId = null): FormSubmission
    {
        $formSubmission = $this->formSubmissionFactory->create($data, $formEntity, $userId);
        $this->em->persist($formSubmission);
        $this->em->flush();

        $this->incFormSubmissionCounter($formEntity);

        return $formSubmission;
    }

    private function getFormSubmissionCounter(Form $formEntity): int
    {
        $session = $this->request->getSession();

        return $session ? $session->get($this->generateSessionFormId($formEntity), 0) : 0;
    }

    private function incFormSubmissionCounter(Form $formEntity): void
    {
        $this->request->getSession()->set(
            $this->generateSessionFormId($formEntity),
            $this->getFormSubmissionCounter($formEntity) + 1
        );
    }

    private function generateSessionFormId(Form $formEntity): string
    {
        return 'form_id_'.$formEntity->getId();
    }
}
