<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Form\Type\ChoiceCollectionType;

/**
 * Class Date.
 *
 * @ORM\Entity()
 *
 * @property string defaultValue
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class Choice extends Field
{
    /**
     * @return string
     */
    public function getChoiceType()
    {
        return $this->getOption('choice_type');
    }

    public function setChoiceType(string $type): void
    {
        $this->setOption('choice_type', $type);
    }

    /**
     * @return int
     */
    public function getChoices() //todo turn back type hinting
    {
        return $this->getOption('choices');
    }

    /**
     * @param int $minLength
     */
    public function setChoices(array $defaultValue): void
    {
        $this->setOption('choices', $defaultValue);
    }

    public function getFormTypeClass()
    {
        return ChoiceCollectionType::class;
    }
}
