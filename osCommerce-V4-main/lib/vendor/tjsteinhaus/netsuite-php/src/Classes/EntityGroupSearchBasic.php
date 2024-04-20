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

class EntityGroupSearchBasic extends SearchRecordBasic {
    /**
     * @var \NetSuite\Classes\SearchStringField
     */
    public $email;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $externalId;
    /**
     * @var \NetSuite\Classes\SearchStringField
     */
    public $externalIdString;
    /**
     * @var \NetSuite\Classes\SearchStringField
     */
    public $groupName;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $groupOwner;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $groupType;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $internalId;
    /**
     * @var \NetSuite\Classes\SearchLongField
     */
    public $internalIdNumber;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $isDynamic;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $isInactive;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $isManufacturingWorkCenter;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $isPrivate;
    /**
     * @var \NetSuite\Classes\SearchDoubleField
     */
    public $laborResources;
    /**
     * @var \NetSuite\Classes\SearchDateField
     */
    public $lastModifiedDate;
    /**
     * @var \NetSuite\Classes\SearchDoubleField
     */
    public $machineResources;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $subsidiary;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $workCalendar;
    /**
     * @var \NetSuite\Classes\SearchCustomFieldList
     */
    public $customFieldList;
    static $paramtypesmap = array(
        "email" => "SearchStringField",
        "externalId" => "SearchMultiSelectField",
        "externalIdString" => "SearchStringField",
        "groupName" => "SearchStringField",
        "groupOwner" => "SearchMultiSelectField",
        "groupType" => "SearchMultiSelectField",
        "internalId" => "SearchMultiSelectField",
        "internalIdNumber" => "SearchLongField",
        "isDynamic" => "SearchBooleanField",
        "isInactive" => "SearchBooleanField",
        "isManufacturingWorkCenter" => "SearchBooleanField",
        "isPrivate" => "SearchBooleanField",
        "laborResources" => "SearchDoubleField",
        "lastModifiedDate" => "SearchDateField",
        "machineResources" => "SearchDoubleField",
        "subsidiary" => "SearchMultiSelectField",
        "workCalendar" => "SearchMultiSelectField",
        "customFieldList" => "SearchCustomFieldList",
    );
}
