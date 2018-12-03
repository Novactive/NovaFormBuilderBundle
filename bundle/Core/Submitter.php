<?php
/**
 * NovaFormBuilder package.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Core;

use Doctrine\ORM\EntityManagerInterface;
use eZ\Publish\Core\MVC\Symfony\Security\User as EzSecurityUser;
use Novactive\Bundle\FormBuilderBundle\Entity\Field\File;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Translation\TranslatorInterface;

class Submitter
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var FileUploaderInterface
     */
    private $fileUploader;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * Submitter constructor.
     */
    public function __construct(
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        SessionInterface $session,
        FileUploaderInterface $fileUploader,
        TokenStorage $tokenStorage
    ) {
        $this->em           = $em;
        $this->translator   = $translator;
        $this->session      = $session;
        $this->fileUploader = $fileUploader;
        $this->tokenStorage = $tokenStorage;
    }

    private function createSubmission(Form $formEntity): FormSubmission
    {
        $data = [];
        foreach ($formEntity->getFields() as $field) {
            $value = $field->getValue();
            if ($field instanceof File) {
                $value = $this->fileUploader->upload($field->getValue());
            }
            $data[] = [
                'name'  => $field->getName(),
                'value' => $value,
                'type'  => $field->getType(),
            ];
        }
        $formSubmission = new FormSubmission();
        $formSubmission->setCreatedAt(new \DateTime());
        $formSubmission->setForm($formEntity);
        $formSubmission->setData($data);

        /* @var TokenInterface $token */
        $token = $this->tokenStorage->getToken();
        $user  = $token->getUser();
        if ($token instanceof UsernamePasswordToken && $user instanceof EzSecurityUser) {
            $formSubmission->setUserId($user->getAPIUser()->content->versionInfo->contentInfo->id);
        }

        return $formSubmission;
    }

    public function canSubmit(FormInterface $form, Form $formEntity): bool
    {
        $maxSubmissions = $formEntity->getMaxSubmissions();
        if (null === $maxSubmissions || $maxSubmissions > $this->getFormSubmissionCounter($formEntity)) {
            return true;
        }
        $form->addError(
            new FormError($this->translator->trans('error.reached_max_submissions', [], 'novaformbuilder'))
        );

        return false;
    }

    public function createAndLogSubmission(Form $formEntity): FormSubmission
    {
        $formSubmission = $this->createSubmission($formEntity);
        $this->em->persist($formSubmission);
        $this->em->flush();

        $this->incFormSubmissionCounter($formEntity);

        return $formSubmission;
    }

    private function getFormSubmissionCounter(Form $formEntity): int
    {
        return (int) $this->session->get($this->generateSessionFormId($formEntity), 0);
    }

    private function incFormSubmissionCounter(Form $formEntity): void
    {
        $this->session->set(
            $this->generateSessionFormId($formEntity),
            $this->getFormSubmissionCounter($formEntity) + 1
        );
    }

    private function generateSessionFormId(Form $formEntity): string
    {
        return 'novaformbuilder_form_id_'.$formEntity->getId();
    }
}
