<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field;

abstract class FieldType implements FieldTypeInterface, FieldFormMapperInterface
{
    public function getIdentifier(): string
    {
        return $this->getEntity()->getType();
    }
}
