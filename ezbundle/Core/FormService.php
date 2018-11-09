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

namespace Novactive\Bundle\eZFormBuilderBundle\Core;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Twig_Environment;

class FormService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct(EntityManagerInterface $entityManager, Twig_Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->twig          = $twig;
    }

    public function save(ArrayCollection $originalFields, Form $formEntity): int
    {
        foreach ($originalFields as $field) {
            /** @var Field $field */
            if (!$formEntity->getFields()->contains($field)) {
                $field->setForm(null);
                $this->entityManager->persist($field);
                $this->entityManager->remove($field);
            }
        }
        $this->entityManager->persist($formEntity);
        $this->entityManager->flush();

        return $formEntity->getId();
    }

    public function removeForm(Form $formEntity): void
    {
        /** @var Field $field */
        foreach ($formEntity->getFields() as $field) {
            $field->setForm(null);
            $this->entityManager->persist($field);
            $this->entityManager->remove($field);
        }
        $this->entityManager->remove($formEntity);
        $this->entityManager->flush();
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderSubmissions(Form $form): string
    {
        return $this->twig->render(
            '@NovaeZFormBuilder/pdfs/submissions.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
