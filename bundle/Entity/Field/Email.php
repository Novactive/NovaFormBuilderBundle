<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Class Email.
 *
 * @ORM\Entity()
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class Email extends Field
{
    public function getFormTypeClass()
    {
        return EmailType::class;
    }
}
