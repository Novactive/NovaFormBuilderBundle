<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Date
 *
 * @ORM\Entity()
 *
 * @property int minLength
 * @property int maxLength
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class Date extends Field
{
    // min max date
}