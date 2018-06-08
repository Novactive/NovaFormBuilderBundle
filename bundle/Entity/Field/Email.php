<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Email
 *
 * @ORM\Entity()
 *
 * @property int minLength
 * @property int maxLength
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class Email extends Field
{
    // send notification to this email: bool
}