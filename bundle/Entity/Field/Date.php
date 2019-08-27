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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;

/**
 * @ORM\Entity
 */
class Date extends Field
{
    public function getDefaultValue(): string
    {
        return (string) $this->getOption('defaultValue');
    }

    public function setDefaultValue(string $defaultValue): self
    {
        $this->setOption('defaultValue', $defaultValue);

        return $this;
    }

    public function getMinValue(): ?DateTime
    {
        return null === $this->getOption('minValue') ? null : new DateTime($this->getOption('minValue')['date']);
    }

    public function setMinValue(DateTime $minValue): self
    {
        $this->setOption('minValue', $minValue);

        return $this;
    }

    public function getMaxValue(): ?DateTime
    {
        return null === $this->getOption('maxValue') ? null : new DateTime($this->getOption('maxValue')['date']);
    }

    public function setMaxValue(DateTime $maxValue): self
    {
        $this->setOption('maxValue', $maxValue);

        return $this;
    }

    public function getValue()
    {
        if (null !== $this->value) {
            return $this->value;
        }
        if ($this->getDefaultValue()) {
            return new DateTime();
        }

        return DateTime::createFromFormat('Y-m-d', '0000-01-01');
    }

    public function setValue($value): Field
    {
        if (null !== $value) {
            $this->value = $value;
        }

        return $this;
    }
}
