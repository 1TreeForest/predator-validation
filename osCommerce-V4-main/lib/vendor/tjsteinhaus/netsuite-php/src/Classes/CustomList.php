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

class CustomList extends Record {
    /**
     * @var string
     */
    public $name;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $owner;
    /**
     * @var boolean
     */
    public $isOrdered;
    /**
     * @var string
     */
    public $description;
    /**
     * @var boolean
     */
    public $isMatrixOption;
    /**
     * @var string
     */
    public $scriptId;
    /**
     * @var boolean
     */
    public $convertToCustomRecord;
    /**
     * @var boolean
     */
    public $isInactive;
    /**
     * @var \NetSuite\Classes\CustomListCustomValueList
     */
    public $customValueList;
    /**
     * @var \NetSuite\Classes\CustomListTranslationsList
     */
    public $translationsList;
    /**
     * @var string
     */
    public $internalId;
    static $paramtypesmap = array(
        "name" => "string",
        "owner" => "RecordRef",
        "isOrdered" => "boolean",
        "description" => "string",
        "isMatrixOption" => "boolean",
        "scriptId" => "string",
        "convertToCustomRecord" => "boolean",
        "isInactive" => "boolean",
        "customValueList" => "CustomListCustomValueList",
        "translationsList" => "CustomListTranslationsList",
        "internalId" => "string",
    );
}
