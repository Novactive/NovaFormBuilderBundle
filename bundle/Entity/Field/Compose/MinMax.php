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

trait MinMax
{
    public function getMin(): int
    {
        return $this->getOption('min') ?? 0;
    }

    public function setMin(int $min): self
    {
        $this->setOption('min', $min);

        return $this;
    }

    public function getMax(): int
    {
        return $this->getOption('max') ?? 10 * 10;
    }

    public function setMax(int $max): self
    {
        $this->setOption('max', $max);

        return $this;
    }
}
