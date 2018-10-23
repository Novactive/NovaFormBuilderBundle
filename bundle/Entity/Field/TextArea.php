<?php
/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;

/**
 * @ORM\Entity
 */
class TextArea extends Field
{
    public function getMinLength(): int
    {
        return $this->getOption('minLength') ?? 50;
    }

    public function setMinLength(int $minLength): self
    {
        $this->setOption('minLength', $minLength);

        return $this;
    }

    public function getMaxLength(): int
    {
        return $this->getOption('maxLength') ?? 500;
    }

    public function setMaxLength(int $maxLength): self
    {
        $this->setOption('maxLength', $maxLength);

        return $this;
    }
}
