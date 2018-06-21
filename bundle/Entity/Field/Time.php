<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

/**
 * Class Time.
 *
 * @ORM\Entity()
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class Time extends Field
{
    public function getFormTypeClass()
    {
        return TimeType::class;
    }
}
