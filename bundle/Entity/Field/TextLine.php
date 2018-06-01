<?php
/**
 * NovaFormBuilderBundle.
 *
 * @package   NovaFormBuilderBundle
 *
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE
 */

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;

/**
 * Class TextLine.
 *
 * @property int minLength
 * @property int maxLength
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class TextLine extends Field
{
    /**
     * @return int
     */
    public function getMinLength(): int
    {
        return $this->getOption('minLength');
    }

    /**
     * @param int $minLength
     */
    public function setMinLength(int $minLength): void
    {
        $this->setOption('minLength', $minLength);
    }

    /**
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->getOption('maxLength');
    }

    /**
     * @param int $maxLength
     */
    public function setMaxLength(int $maxLength): void
    {
        $this->setOption('maxLength', $maxLength);
    }
}
