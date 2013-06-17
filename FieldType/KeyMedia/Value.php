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
    /**
     * JSON containing all the data
     *
     * @var string
     */
    public $json;

    /**
     * Construct a new Value object and initialize it with its $link and optional $text
     *
     * @param string $json
     */
    public function __construct($json = null)
    {
        $this->json = $json;
    }

    /**
     * @see \eZ\Publish\Core\FieldType\Value
     */
    public function __toString()
    {
        return (string)$this->json;
    }
}
