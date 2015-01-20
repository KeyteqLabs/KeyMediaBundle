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
use eZ\Publish\Core\FieldType\Value as CoreValue;
use eZ\Publish\SPI\FieldType\Value as SpiValue;
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
    public function getName(SpiValue $value)
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
     * @return Value
     */
    public function getEmptyValue()
    {
        return new Value();
    }

    /**
     * Implements the core of {@see acceptValue()}.
     *
     * @param mixed $inputValue
     *
     * @throws InvalidArgumentType
     * @return Value The potentially converted and structurally plausible value.
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
     * @param CoreValue $value
     *
     * @return array
     */
    protected function getSortInfo(CoreValue $value)
    {
        return false;
    }

    /**
     * Converts a $hash to the Value defined by the field type
     *
     * @param mixed $hash
     *
     * @return Value $value
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
     * @param SpiValue $value
     *
     * @return mixed
     */
    public function toHash(SpiValue $value)
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
    public function toPersistenceValue(SpiValue $value)
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
                'data' => (string)$value,
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

    /**
     * Inspects given $inputValue and potentially converts it into a dedicated value object.
     *
     * If given $inputValue could not be converted or is already an instance of dedicate value object,
     * the method should simply return it.
     *
     * This is an operation method for {@see acceptValue()}.
     *
     * Example implementation:
     * <code>
     *  protected function createValueFromInput( $inputValue )
     *  {
     *      if ( is_array( $inputValue ) )
     *      {
     *          $inputValue = \eZ\Publish\Core\FieldType\CookieJar\Value( $inputValue );
     *      }
     *
     *      return $inputValue;
     *  }
     * </code>
     *
     * @param mixed $inputValue
     *
     * @return mixed The potentially converted input value.
     */
    protected function createValueFromInput($inputValue)
    {
        return $inputValue;
    }

    /**
     * Throws an exception if value structure is not of expected format.
     *
     * Note that this does not include validation after the rules
     * from validators, but only plausibility checks for the general data
     * format.
     *
     * This is an operation method for {@see acceptValue()}.
     *
     * Example implementation:
     * <code>
     *  protected function checkValueStructure( Value $value )
     *  {
     *      if ( !is_array( $value->cookies ) )
     *      {
     *          throw new InvalidArgumentException( "An array of assorted cookies was expected." );
     *      }
     *  }
     * </code>
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If the value does not match the expected structure.
     *
     * @param \eZ\Publish\Core\FieldType\Value $value
     *
     * @return void
     */
    protected function checkValueStructure(CoreValue $value) {}
}
