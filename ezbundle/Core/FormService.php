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

class FormService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(ArrayCollection $originalFields, Form $formEntity): void
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
    }
}
