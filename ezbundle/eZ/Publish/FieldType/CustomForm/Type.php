<?php

/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Maxim Strukov <m.strukov@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

namespace Novactive\Bundle\eZFormBuilderBundle\eZ\Publish\FieldType\CustomForm;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\Core\FieldType\Value as CoreValue;
use eZ\Publish\SPI\FieldType\Nameable;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue as PersistenceValue;

class Type extends FieldType implements Nameable
{
    public function getFieldTypeIdentifier(): string
    {
        return 'ezcustomform';
    }

    /**
     * @return string
     */
    public function getName(SPIValue $value)
    {
        throw new \RuntimeException(
            'Name generation provided via NameableField set via "ezpublish.fieldType.nameable" service tag'
        );
    }

    protected function getSortInfo(CoreValue $value): int
    {
        return (int) $value->formId;
    }

    protected function createValueFromInput($inputValue)
    {
        if (\is_int($inputValue)) {
            $inputValue = new Value(['formId' => $inputValue]);
        }

        return $inputValue;
    }

    /**
     * @throws InvalidArgumentType
     */
    protected function checkValueStructure(CoreValue $value)
    {
        if (!\is_int($value->formId)) {
            throw new InvalidArgumentType(
                '$value->formId',
                'int',
                $value->formId
            );
        }
    }

    public function getEmptyValue()
    {
        return new Value();
    }

    public function fromHash($hash)
    {
        if (null === $hash) {
            return $this->getEmptyValue();
        }

        return new Value($hash);
    }

    public function toHash(SPIValue $value): ?array
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        return [
            'formId' => $value->formId,
        ];
    }

    public function toPersistenceValue(SPIValue $value): PersistenceValue
    {
        if (null === $value) {
            return new PersistenceValue(
                [
                    'data'         => null,
                    'externalData' => null,
                    'sortKey'      => null,
                ]
            );
        }

        return new PersistenceValue(
            [
                'data'    => $this->toHash($value),
                'sortKey' => $this->getSortInfo($value),
            ]
        );
    }

    /**
     * Converts a persistence $fieldValue to a Value.
     * This method builds a field type value from the $data and $externalData properties.
     */
    public function fromPersistenceValue(PersistenceValue $fieldValue): Value
    {
        if (null === $fieldValue->data) {
            return $this->getEmptyValue();
        }

        return new Value($fieldValue->data);
    }

    /**
     * @param string $languageCode
     */
    public function getFieldName(SPIValue $value, FieldDefinition $fieldDefinition, $languageCode): int
    {
        return (int) $value->formId;
    }
}
