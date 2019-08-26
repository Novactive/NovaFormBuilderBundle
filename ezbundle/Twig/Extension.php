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

    /**
     * Extension constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('get_form', [$this, 'getForm']),
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

    public function isFormAvailable(?int $formId): bool
    {
        /* @var Form $form */
        $form = $this->entityManager->getRepository(Form::class)->findOneBy(['id' => $formId]);

        /** @var \DateTime $dateStartSubmission */
        $dateStartSubmission = $form->getDateStartSubmission();
        $dateStartSubmissionIsNull = !$dateStartSubmission || $dateStartSubmission->getTimestamp() == null;
        /** @var \DateTime $dateEndSubmission */
        $dateEndSubmission = $form->getDateEndSubmission();
        $dateEndSubmissionIsNull = !$dateEndSubmission || $dateEndSubmission->getTimestamp() == null;

        /** @var \DateTime $now */
        $now = new \DateTime();

        return (
            ($dateStartSubmissionIsNull && $dateEndSubmissionIsNull) // both are null
            || (!$dateStartSubmissionIsNull && $dateStartSubmission < $now && $dateEndSubmissionIsNull) // dateStart is past and dateEnd is null
            || ($dateStartSubmissionIsNull && !$dateEndSubmissionIsNull && $dateEndSubmission > $now) // dateStart is null and dateEnd is not past
            || (!$dateStartSubmissionIsNull && $dateStartSubmission < $now && !$dateEndSubmissionIsNull && $dateEndSubmission > $now) // both are not null and now is between both dates
        );
    }
}
