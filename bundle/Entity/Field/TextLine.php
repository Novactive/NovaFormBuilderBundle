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

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class TextLine.
 *
 * @ORM\Entity()
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

    public function getTypeClass()
    {
        return TextType::class;
    }
}
