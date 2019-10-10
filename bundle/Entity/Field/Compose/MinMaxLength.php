<?php
/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/FormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field\Compose;

trait MinMaxLength
{
    public function getMinLength(): int
    {
        return (int) $this->getOption('minLength');
    }

    public function setMinLength(int $minLength): self
    {
        $this->setOption('minLength', $minLength);

        return $this;
    }

    public function getMaxLength(): int
    {
        return (int) $this->getOption('maxLength');
    }

    public function setMaxLength(int $maxLength): self
    {
        $this->setOption('maxLength', $maxLength);

        return $this;
    }
}
