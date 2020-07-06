<?php
/**
 * NovaFormBuilder package.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Core;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use Novactive\Bundle\FormBuilderBundle\Core\Field\FieldTypeInterface;

class FieldTypeRegistry
{
    /**
     * @var FieldTypeInterface[]
     */
    private $fieldTypes = [];

    public function __construct(iterable $fieldTypes)
    {
        foreach ($fieldTypes as $fieldType) {
            $this->addFieldType($fieldType);
        }
    }

    public function addFieldType(FieldTypeInterface $fieldType): void
    {
        $this->fieldTypes[$fieldType->getIdentifier()] = $fieldType;
    }

    public function getFieldTypesIdentifier(): array
    {
        return array_keys($this->fieldTypes);
    }

    /**
     * @return FieldTypeInterface[]
     */
    public function getFieldTypes(): array
    {
        return $this->fieldTypes;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getFieldTypeByIdentifier(string $identifier): FieldTypeInterface
    {
        if (!isset($this->fieldTypes[$identifier])) {
            $msg = sprintf('Form field type with identifier "%s" doesn\'t exist', $identifier);
            throw new InvalidArgumentException('identifier', $msg);
        }

        return $this->fieldTypes[$identifier];
    }
}
