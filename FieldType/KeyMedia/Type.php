<?php

/**
 * The KeyMedia FieldType definition class
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 *
 */

namespace KTQ\Bundle\KeyMediaBundle\FieldType\KeyMedia;

use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;

class Type extends FieldType
{
    protected $validatorConfigurationSchema = array();
    protected $settingsSchema = array(
        'keymedia' => array(
            'type' => 'object',
            'default' => null
        )
    );
    /**
     * Returns the field type identifier for this field type
     *
     * @return string
     */
    public function getFieldTypeIdentifier()
    {
        return 'keymedia';
    }

    /**
     * Returns the name of the given field value.
     *
     * It will be used to generate content name and url alias if current field is designated
     * to be used in the content name/urlAlias pattern.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function getName($value)
    {
        if ($value === null) {
            return '';
        }
        $value = $this->acceptValue($value);
        return (string)$value->value;
    }

    /**
     * Returns the fallback default value of field type when no such default
     * value is provided in the field definition in content types.
     *
     * @return KTQ\Bundle\KeyMediaBundle\FieldType\KeyMedia\Value
     */
    public function getEmptyValue()
    {
        return new Value;
    }

    /**
     * Implements the core of {@see acceptValue()}.
     *
     * @param mixed $inputValue
     *
     * @return KTQ\Bundle\KeyMediaBundle\FieldType\KeyMedia\Value The potentially converted and structurally plausible value.
     */
    protected function internalAcceptValue($inputValue)
    {
        if (is_string($inputValue)) {
            $inputValue = new Value($inputValue);
        }
        else if (!$inputValue instanceof Value) {
            throw new InvalidArgumentType(
                '$inputValue',
                'KTQ\\Bundle\\KeyMediaBundle\\FieldType\\KeyMedia\\Value',
                $inputValue
            );
        }

        if (isset($inputValue->value) && !is_string($inputValue->value)) {
            throw new InvalidArgumentType(
                '$inputValue->value',
                'string',
                $inputValue->value
            );
        }

        return $inputValue;
    }

    /**
     * Returns information for FieldValue->$sortKey relevant to the field type.
     *
     * @todo Sort seems to not be supported by this FieldType, is this handled correctly?
     *
     * @return array
     */
    protected function getSortInfo($value)
    {
        return false;
    }

    /**
     * Converts a $hash to the Value defined by the field type
     *
     * @param mixed $hash
     *
     * @return KTQ\Bundle\KeyMediaBundle\FieldType\KeyMedia\Value $value
     */
    public function fromHash($hash)
    {
        if ($hash === null)
            return $hash;

        if (isset($hash['value']))
            return new Value($hash['value']);

        return $this->getEmptyValue();
    }

    /**
     * Converts a Value to a hash
     *
     * @param KTQ\Bundle\KeyMediaBundle\FieldType\KeyMedia\Value $value
     *
     * @return mixed
     */
    public function toHash($value)
    {
        if ($this->isEmptyValue($value))
            return null;

        return array('value' => $value->value);
    }

    /**
     * Converts a $value to a persistence value.
     *
     * In this method the field type puts the data which is stored in the field of content in the repository
     * into the property FieldValue::data. The format of $data is a primitive, an array (map) or an object, which
     * is then canonically converted to e.g. json/xml structures by future storage engines without
     * further conversions. For mapping the $data to the legacy database an appropriate Converter
     * (implementing eZ\Publish\Core\Persistence\Legacy\FieldValue\Converter) has implemented for the field
     * type. Note: $data should only hold data which is actually stored in the field. It must not
     * hold data which is stored externally.
     *
     * The $externalData property in the FieldValue is used for storing data externally by the
     * FieldStorage interface method storeFieldData.
     *
     * The FieldValuer::sortKey is build by the field type for using by sort operations.
     *
     * @see \eZ\Publish\SPI\Persistence\Content\FieldValue
     *
     * @param mixed $value The value of the field type
     *
     * @return \eZ\Publish\SPI\Persistence\Content\FieldValue the value processed by the storage engine
     */
    public function toPersistenceValue($value)
    {
        if ($value === null) {
            return new FieldValue(
                array(
                    'data' => array(),
                    'externalData' => null,
                    'sortKey' => null
                )
            );
        }

        return new FieldValue(
            array(
                'data' => array(
                    'value' => $value->value
                ),
                'externalData' => null,
                'sortKey' => $this->getSortInfo($value)
            )
        );
    }

    /**
     * Converts a persistence $fieldValue to a Value
     *
     * This method builds a field type value from the $data and $externalData properties.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\FieldValue $fieldValue
     *
     * @return mixed
     */
    public function fromPersistenceValue(FieldValue $fieldValue)
    {
        if ($fieldValue->data === null)
            return null;

        $v = new Value(
            $fieldValue->data
        );
        return $v;
    }
}
