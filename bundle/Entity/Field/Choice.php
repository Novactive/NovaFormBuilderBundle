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
class Choice extends Field
{
    use Field\Compose\AutoComplete;
    use Field\Compose\Identifier;

    public function getChoiceType(): string
    {
        return (string) $this->getOption('choice_type');
    }

    public function setChoiceType(string $type): self
    {
        $this->setOption('choice_type', $type);

        return $this;
    }

    public function getChoices(): array
    {
        return $this->getOption('choices') ?? [];
    }

    public function setChoices(array $choices): self
    {
        $this->setOption('choices', $choices);

        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->getOption('defaultValue', null);
    }

    public function setDefaultValue($defaultValue): self
    {
        $this->setOption('defaultValue', $defaultValue);

        return $this;
    }

    public function getValue()
    {
        return $this->value ?? $this->getDefaultValue();
    }
}
