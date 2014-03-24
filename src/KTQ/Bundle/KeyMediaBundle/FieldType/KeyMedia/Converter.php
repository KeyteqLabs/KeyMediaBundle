<?php

namespace KTQ\Bundle\KeyMediaBundle\FieldType\KeyMedia;

use eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter as BaseConverter;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\Core\FieldType\FieldSettings;

class Converter implements BaseConverter
{
    /**
     * Factory for current class
     *
     * @note Class should instead be configured as service if it gains dependencies.
     *
     * @return self
     */
    static public function create()
    {
        return new self;
    }

    /**
     * Converts data from $value to $storageFieldValue
     *
     * @param FieldValue $value
     * @param StorageFieldValue $storageFieldValue
     */
    public function toStorageValue(FieldValue $value, StorageFieldValue $storageFieldValue)
    {
        $value->dataText = $value->data;
        $value->sortKeyString = $value->sortKey;
    }

    /**
     * Converts data from $value to $fieldValue
     *
     * @param StorageFieldValue $value
     * @param FieldValue $fieldValue
     */
    public function toFieldValue(StorageFieldValue $value, FieldValue $fieldValue)
    {
        $fieldValue->data = $value->dataText;
        $fieldValue->sortKey = $value->sortKeyString;
    }

    /**
     * Converts field definition data in $fieldDef into $storageFieldDef
     *
     * @param FieldDefinition $fieldDef
     * @param StorageFieldDefinition $storageDef
     */
    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef)
    {
        $storageDef->dataText5 = json_encode($fieldDef->defaultValue->data);
    }

    /**
     * Converts field definition data in $storageDef into $fieldDef
     *
     * @param StorageFieldDefinition $storageDef
     * @param FieldDefinition $fieldDef
     */
    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef)
    {
        $defaultValue = null;
        if ($storageDef->dataText5) {
            $defaultValue = json_decode($storageDef->dataText5);
        }

        $fieldDef->defaultValue->data = $defaultValue;


        $fieldDef->defaultValue->data = $storageDef->dataText5 ?: null;
        $fieldDef->fieldTypeConstraints->validators = array();
        $fieldDef->fieldTypeConstraints->fieldSettings = new FieldSettings();
    }

    /**
     * Returns the name of the index column in the attribute table
     *
     * @return string
     */
    public function getIndexColumn()
    {
        return 'sort_key_string';
    }
}
