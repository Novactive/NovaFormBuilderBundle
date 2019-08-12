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
class Paragraph extends Field
{
    public function getValue()
    {
        if (null !== $this->getOption('value')) {
            return $this->getOption('value');
        }

        return null;
    }

    public function setValue($value): Field
    {
        $this->setOption('value', $value);

        return $this;
    }
}
