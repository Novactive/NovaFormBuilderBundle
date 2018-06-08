<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Time
 *
 * @ORM\Entity()
 *
 * @property int minLength
 * @property int maxLength
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class Time extends Field
{
    // min max time
}