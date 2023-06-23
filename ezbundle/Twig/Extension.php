<?php

/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Maxim Strukov <m.strukov@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\eZFormBuilderBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use eZ\Publish\Core\Repository\Permission\PermissionResolver;
use Novactive\Bundle\eZFormBuilderBundle\Core\FormService;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;

/**
 * Class Extension.
 */
class Extension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @var FormService */
    private $formService;

    /** @var PermissionResolver */
    private $permissionResolver;

    /**
     * Extension constructor.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormService $formService,
        PermissionResolver $permissionResolver
    ) {
        $this->entityManager      = $entityManager;
        $this->formService        = $formService;
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('get_form', [$this, 'getForm']),
            new \Twig_Function('can_read_form_submissions', [$this, 'canReadFormSubmissions']),
            new \Twig_Function('is_form_available', [$this, 'isFormAvailable']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals(): array
    {
        return [];
    }

    public function getForm(?int $formId): ?Form
    {
        /* @var Form $form */
        $form = $this->entityManager->getRepository(Form::class)->findOneBy(['id' => $formId]);

        return $form;
    }

    public function canReadFormSubmissions(int $formId): bool
    {
        try {
            $associatedContents = $this->formService->associatedContents($formId);
            foreach ($associatedContents as $associatedContent) {
                if (!$this->permissionResolver->canUser('form', 'read_submissions', $associatedContent)) {
                    return false;
                }
            }
        } catch (Exception $exception) {
            // TODO $this->logger->warn()
            return false;
        }

        return true;
    }

    public function isFormAvailable(?int $formId): bool
    {
        /* @var Form $form */
        $form = $this->entityManager->getRepository(Form::class)->findOneBy(['id' => $formId]);

        /** @var \DateTime $dateStartSubmission */
        $dateStartSubmission       = $form->getDateStartSubmission();
        $dateStartSubmissionIsNull = !$dateStartSubmission || null == $dateStartSubmission->getTimestamp();
        /** @var \DateTime $dateEndSubmission */
        $dateEndSubmission       = $form->getDateEndSubmission();
        $dateEndSubmissionIsNull = !$dateEndSubmission || null == $dateEndSubmission->getTimestamp();

        /** @var \DateTime $now */
        $now = new \DateTime();

        return
            // both are null
            ($dateStartSubmissionIsNull && $dateEndSubmissionIsNull)
            // dateStart is past and dateEnd is null
            || (!$dateStartSubmissionIsNull && $dateStartSubmission < $now && $dateEndSubmissionIsNull)
            // dateStart is null and dateEnd is not past
            || ($dateStartSubmissionIsNull && !$dateEndSubmissionIsNull && $dateEndSubmission > $now)
            // both are not null and now is between both dates
            || (!$dateStartSubmissionIsNull && $dateStartSubmission < $now
                && !$dateEndSubmissionIsNull && $dateEndSubmission > $now)
        ;
    }
}
