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

namespace Novactive\Bundle\FormBuilderBundle\Core\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Util\StringUtil;

abstract class FieldType implements FieldTypeInterface, FieldTypeMapperInterface
{
    public function getIdentifier(): string
    {
        return StringUtil::fqcnToBlockPrefix($this->getEntityClass());
    }

    abstract public function getEntityClass(): string;

    public function supportsEntity(Field $field): bool
    {
        return \get_class($field) === $this->getEntityClass();
    }

    public function newEntity(array $properties = []): Field
    {
        $class = $this->getEntityClass();

        return new $class($properties);
    }

    public function canExport(): bool
    {
        return true;
    }
}
