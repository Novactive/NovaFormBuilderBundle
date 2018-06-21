<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;

/**
 * Class Email.
 *
 * @ORM\Entity()
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class Email extends Field
{
    // send notification to this email: bool
}
