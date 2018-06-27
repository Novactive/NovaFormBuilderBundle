<?php


namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;


use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class Choice extends Field
{
    /**
     * @return int
     */
    public function getChoices(): string
    {
        return $this->getOption('choices');
    }

    /**
     * @param int $minLength
     */
    public function setChoices(string $defaultValue): void
    {
        $this->setOption('choices', []);
    }

    public function getFormTypeClass()
    {
        return CollectionType::class;
    }
}