<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field;

class FieldTypeRegistry
{
    /** @var FieldTypeInterface[] */
    protected $fieldTypes = [];

    /**
     * FieldTypeRegistry constructor.
     *
     * @param FieldTypeInterface|iterable $fieldTypes
     */
    public function __construct(iterable $fieldTypes)
    {
        foreach ($fieldTypes as $fieldType) {
            $this->addFieldType($fieldType);
        }
    }

    /**
     * @param FieldTypeInterface $fieldType
     */
    public function addFieldType(FieldTypeInterface $fieldType): void
    {
        $this->fieldTypes[$fieldType->getIdentifier()] = $fieldType;
    }

    /**
     * @return array
     */
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
}
