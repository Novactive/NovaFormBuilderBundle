<?php
/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;

abstract class FieldType implements FieldTypeInterface, FieldTypeMapperInterface
{
    public function getIdentifier(): string
    {
        return $this->getEntity()->getType();
    }

    abstract public function getEntity(array $properties = []): Field;
}
