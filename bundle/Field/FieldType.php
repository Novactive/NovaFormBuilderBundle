<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field;

use Symfony\Component\Form\Util\StringUtil;

abstract class FieldType implements FieldTypeInterface, FieldFormMapperInterface
{
    public function getIdentifier(): string
    {
        return StringUtil::fqcnToBlockPrefix(get_class($this->getEntity()));
    }
}
