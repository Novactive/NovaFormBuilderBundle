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
    use Field\Compose\MinMaxLength;
    use Field\Compose\AutoComplete;

    public function getDefaultValue(): ?string
    {
        return $this->getOption('defaultValue', '');
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
