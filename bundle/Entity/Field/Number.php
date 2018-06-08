<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Number
 *
 * @ORM\Entity()
 *
 * @property int minLength
 * @property int maxLength
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class Number extends Field
{
    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->getOption('min');
    }

    /**
     * @param int $minLength
     */
    public function setMin(int $min): void
    {
        $this->setOption('min', $min);
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->getOption('max');
    }

    /**
     * @param int $maxLength
     */
    public function setMax(int $max): void
    {
        $this->setOption('max', $max);
    }
}