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

use eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;

class LegacyConverter implements Converter
{
    public function toStorageValue(FieldValue $value, StorageFieldValue $storageFieldValue): void
    {
        $storageFieldValue->dataInt       = json_encode($value->data);
        $storageFieldValue->sortKeyString = $value->sortKey;
    }

    public function toFieldValue(StorageFieldValue $value, FieldValue $fieldValue): void
    {
        $fieldValue->data    = json_decode($value->dataInt, true);
        $fieldValue->sortKey = $value->sortKeyString;
    }

    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef): void
    {
    }

    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef): void
    {
    }

    /**
     * Returns the name of the index column in the attribute table.
     *
     * Returns the name of the index column the datatype uses, which is either
     * "sort_key_int" or "sort_key_string". This column is then used for
     * filtering and sorting for this type.
     *
     * If the indexing is not supported, this method must return false.
     */
    public function getIndexColumn(): string
    {
        return 'sort_key_int';
    }
}
