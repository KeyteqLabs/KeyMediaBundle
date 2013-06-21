<?php
/**
 * The KeyMedia FieldType value class
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 *
 */

namespace KTQ\Bundle\KeyMediaBundle\FieldType\KeyMedia;

use eZ\Publish\Core\FieldType\Value as BaseValue;

class Value extends BaseValue
{
    public $id, $scalesTo, $ending, $file, $versions;

    /**
     * Construct a new Value object and initialize it with its $link and optional $text
     *
     * @param string $value
     */
    public function __construct($value = null)
    {
        if ($value) {
            $data = json_decode($value);
            foreach ($data as $key => $val) {
                if (property_exists($this, $key))
                    $this->$key = $val;
            }
        }
    }

    /**
     * @see \eZ\Publish\Core\FieldType\Value
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
