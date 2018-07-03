<?php


namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Form\Type\ChoiceCollectionType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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