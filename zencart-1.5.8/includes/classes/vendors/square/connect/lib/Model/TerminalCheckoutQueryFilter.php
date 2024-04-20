<?php
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace SquareConnect\Model;

use \ArrayAccess;
/**
 * TerminalCheckoutQueryFilter Class Doc Comment
 *
 * @category Class
 * @package  SquareConnect
 * @author   Square Inc.
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link     https://squareup.com/developers
 * Note: This endpoint is in beta.
 */
class TerminalCheckoutQueryFilter implements ArrayAccess
{
    /**
      * Array of property to type mappings. Used for (de)serialization 
      * @var string[]
      */
    static $swaggerTypes = array(
        'device_id' => 'string',
        'created_at' => '\SquareConnect\Model\TimeRange',
        'status' => 'string'
    );
  
    /** 
      * Array of attributes where the key is the local name, and the value is the original name
      * @var string[] 
      */
    static $attributeMap = array(
        'device_id' => 'device_id',
        'created_at' => 'created_at',
        'status' => 'status'
    );
  
    /**
      * Array of attributes to setter functions (for deserialization of responses)
      * @var string[]
      */
    static $setters = array(
        'device_id' => 'setDeviceId',
        'created_at' => 'setCreatedAt',
        'status' => 'setStatus'
    );
  
    /**
      * Array of attributes to getter functions (for serialization of requests)
      * @var string[]
      */
    static $getters = array(
        'device_id' => 'getDeviceId',
        'created_at' => 'getCreatedAt',
        'status' => 'getStatus'
    );
  
    /**
      * $device_id `TerminalCheckout`s associated with a specific device. If no device is specified then all `TerminalCheckout`s for the merchant will be displayed.
      * @var string
      */
    protected $device_id;
    /**
      * $created_at Time range for the beginning of the reporting period. Inclusive. Default: The current time minus one day.
      * @var \SquareConnect\Model\TimeRange
      */
    protected $created_at;
    /**
      * $status Filtered results with the desired status of the `TerminalCheckout` Options: PENDING, IN\\_PROGRESS, CANCELED, COMPLETED
      * @var string
      */
    protected $status;

    /**
     * Constructor
     * @param mixed[] $data Associated array of property value initializing the model
     */
    public function __construct(array $data = null)
    {
        if ($data != null) {
            if (isset($data["device_id"])) {
              $this->device_id = $data["device_id"];
            } else {
              $this->device_id = null;
            }
            if (isset($data["created_at"])) {
              $this->created_at = $data["created_at"];
            } else {
              $this->created_at = null;
            }
            if (isset($data["status"])) {
              $this->status = $data["status"];
            } else {
              $this->status = null;
            }
        }
    }
    /**
     * Gets device_id
     * @return string
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }
  
    /**
     * Sets device_id
     * @param string $device_id `TerminalCheckout`s associated with a specific device. If no device is specified then all `TerminalCheckout`s for the merchant will be displayed.
     * @return $this
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;
        return $this;
    }
    /**
     * Gets created_at
     * @return \SquareConnect\Model\TimeRange
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
  
    /**
     * Sets created_at
     * @param \SquareConnect\Model\TimeRange $created_at Time range for the beginning of the reporting period. Inclusive. Default: The current time minus one day.
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }
    /**
     * Gets status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
  
    /**
     * Sets status
     * @param string $status Filtered results with the desired status of the `TerminalCheckout` Options: PENDING, IN\\_PROGRESS, CANCELED, COMPLETED
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset 
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }
  
    /**
     * Gets offset.
     * @param  integer $offset Offset 
     * @return mixed 
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }
  
    /**
     * Sets value based on offset.
     * @param  integer $offset Offset 
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }
  
    /**
     * Unsets offset.
     * @param  integer $offset Offset 
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
  
    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) {
            return json_encode(\SquareConnect\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        } else {
            return json_encode(\SquareConnect\ObjectSerializer::sanitizeForSerialization($this));
        }
    }
}
