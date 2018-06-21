<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Class Date.
 *
 * @ORM\Entity()
 *
 * @property string defaultValue
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class Date extends Field
{
    // min max date

    /**
     * @return int
     */
    public function getDefaultValue(): string
    {
        return $this->getOption('defaultValue');
    }

    /**
     * @param int $minLength
     */
    public function setDefaultValue(string $defaultValue): void
    {
        $this->setOption('defaultValue', $defaultValue);
    }

    public function getFormTypeClass()
    {
        return DateType::class;
    }
}
