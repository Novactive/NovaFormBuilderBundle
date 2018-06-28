<?php

namespace Novactive\Bundle\FormBuilderBundle\Service;

use Doctrine\ORM\EntityManager;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission;
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
     * @param EntityManager $em
     * @param TranslatorInterface $translator
     * @param RequestStack $requestStack
     * @param FormSubmissionFactory $formSubmissionFactory
     */
    function __construct(
        EntityManager $em,
        TranslatorInterface $translator,
        RequestStack $requestStack,
        FormSubmissionFactory $formSubmissionFactory
    ) {
        $this->em = $em;
        $this->translator = $translator;
        $this->request = $requestStack->getCurrentRequest();
        $this->formSubmissionFactory = $formSubmissionFactory;
    }

    /**
     * @param FormInterface $form
     * @param Form $formEntity
     * @return bool
     */
    public function checkSubmissionAvailability(FormInterface $form, Form $formEntity): bool
    {
        if ($formEntity->getMaxSubmissions() > $this->getFormSubmissionCounter($formEntity)) {
            return true;
        }
        $form->addError(new FormError($this->translator->trans('novaformbuilder_error.reached_max_submissions')));

        return false;
    }

    /**
     * @param array $data
     * @param Form $formEntity
     * @param int|null $userId
     * @return FormSubmission
     */
    public function createAndLogSubmission(array $data, Form $formEntity, ?int $userId = null): FormSubmission
    {
        $formSubmission = $this->formSubmissionFactory->create($data, $formEntity, $userId);
        $this->em->persist($formSubmission);
        $this->em->flush();

        $this->incFormSubmissionCounter($formEntity);

        return $formSubmission;
    }

    /**
     * @param Form $formEntity
     * @return int
     */
    private function getFormSubmissionCounter(Form $formEntity): int
    {
        $session = $this->request->getSession();
        return $session ? $session->get("form_id_{$formEntity->getId()}", 0) : 0;
    }

    /**
     * @param Form $formEntity
     */
    private function incFormSubmissionCounter(Form $formEntity): void
    {
        $this->request->getSession()
            ->set("form_id_{$formEntity->getId()}", $this->getFormSubmissionCounter($formEntity) +1);
    }
}