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

class TimeEntrySearchBasic extends SearchRecordBasic {
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $approvalStatus;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $billable;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $billingClass;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $billingStatus;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $class;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $customer;
    /**
     * @var \NetSuite\Classes\SearchDateField
     */
    public $date;
    /**
     * @var \NetSuite\Classes\SearchDateField
     */
    public $dateCreated;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $department;
    /**
     * @var \NetSuite\Classes\SearchDoubleField
     */
    public $duration;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $employee;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $exempt;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $externalId;
    /**
     * @var \NetSuite\Classes\SearchStringField
     */
    public $externalIdString;
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
    public $isCharged;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $item;
    /**
     * @var \NetSuite\Classes\SearchDateField
     */
    public $lastModified;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $location;
    /**
     * @var \NetSuite\Classes\SearchStringField
     */
    public $memo;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $nextApprover;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $paidByPayroll;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $paidExternally;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $payItem;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $productive;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $subsidiary;
    /**
     * @var \NetSuite\Classes\SearchEnumMultiSelectField
     */
    public $type;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $utilized;
    /**
     * @var \NetSuite\Classes\SearchCustomFieldList
     */
    public $customFieldList;
    static $paramtypesmap = array(
        "approvalStatus" => "SearchMultiSelectField",
        "billable" => "SearchBooleanField",
        "billingClass" => "SearchMultiSelectField",
        "billingStatus" => "SearchBooleanField",
        "class" => "SearchMultiSelectField",
        "customer" => "SearchMultiSelectField",
        "date" => "SearchDateField",
        "dateCreated" => "SearchDateField",
        "department" => "SearchMultiSelectField",
        "duration" => "SearchDoubleField",
        "employee" => "SearchMultiSelectField",
        "exempt" => "SearchBooleanField",
        "externalId" => "SearchMultiSelectField",
        "externalIdString" => "SearchStringField",
        "internalId" => "SearchMultiSelectField",
        "internalIdNumber" => "SearchLongField",
        "isCharged" => "SearchBooleanField",
        "item" => "SearchMultiSelectField",
        "lastModified" => "SearchDateField",
        "location" => "SearchMultiSelectField",
        "memo" => "SearchStringField",
        "nextApprover" => "SearchMultiSelectField",
        "paidByPayroll" => "SearchBooleanField",
        "paidExternally" => "SearchBooleanField",
        "payItem" => "SearchMultiSelectField",
        "productive" => "SearchBooleanField",
        "subsidiary" => "SearchMultiSelectField",
        "type" => "SearchEnumMultiSelectField",
        "utilized" => "SearchBooleanField",
        "customFieldList" => "SearchCustomFieldList",
    );
}
