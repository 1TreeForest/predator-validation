<?php
/**
 * This file is part of the netsuitephp/netsuite-php library
 * AND originally from the NetSuite PHP Toolkit.
 *
 * New content:
 * @package    ryanwinchester/netsuite-php
 * @copyright  Copyright (c) Ryan Winchester
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @link       https://github.com/netsuitephp/netsuite-php
 *
 * Original content:
 * @copyright  Copyright (c) NetSuite Inc.
 * @license    https://raw.githubusercontent.com/netsuitephp/netsuite-php/master/original/NetSuite%20Application%20Developer%20License%20Agreement.txt
 * @link       http://www.netsuite.com/portal/developers/resources/suitetalk-sample-applications.shtml
 */

namespace NetSuite\Classes;

class Address extends Record {
    /**
     * @var string
     */
    public $internalId;
    /**
     * @var \NetSuite\Classes\Country::*
     */
    public $country;
    /**
     * @var string
     */
    public $attention;
    /**
     * @var string
     */
    public $addressee;
    /**
     * @var string
     */
    public $addrPhone;
    /**
     * @var string
     */
    public $addr1;
    /**
     * @var string
     */
    public $addr2;
    /**
     * @var string
     */
    public $addr3;
    /**
     * @var string
     */
    public $city;
    /**
     * @var string
     */
    public $state;
    /**
     * @var string
     */
    public $zip;
    /**
     * @var string
     */
    public $addrText;
    /**
     * @var boolean
     */
    public $override;
    /**
     * @var \NetSuite\Classes\CustomFieldList
     */
    public $customFieldList;
    static $paramtypesmap = array(
        "internalId" => "string",
        "country" => "Country",
        "attention" => "string",
        "addressee" => "string",
        "addrPhone" => "string",
        "addr1" => "string",
        "addr2" => "string",
        "addr3" => "string",
        "city" => "string",
        "state" => "string",
        "zip" => "string",
        "addrText" => "string",
        "override" => "boolean",
        "customFieldList" => "CustomFieldList",
    );
}
