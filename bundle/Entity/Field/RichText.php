<?php
/**
 * @copyright Novactive
 * Date: 30/04/2021
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;

/**
 * @ORM\Entity
 */
class RichText extends Field
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
