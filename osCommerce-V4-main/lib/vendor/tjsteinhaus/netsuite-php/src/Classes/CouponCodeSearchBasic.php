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

class CouponCodeSearchBasic extends SearchRecordBasic {
    /**
     * @var \NetSuite\Classes\SearchStringField
     */
    public $code;
    /**
     * @var \NetSuite\Classes\SearchDateField
     */
    public $dateSent;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $externalId;
    /**
     * @var \NetSuite\Classes\SearchStringField
     */
    public $externalIdString;
    /**
     * @var \NetSuite\Classes\SearchLongField
     */
    public $id;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $internalId;
    /**
     * @var \NetSuite\Classes\SearchLongField
     */
    public $internalIdNumber;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $promotion;
    /**
     * @var \NetSuite\Classes\SearchMultiSelectField
     */
    public $recipient;
    /**
     * @var \NetSuite\Classes\SearchLongField
     */
    public $useCount;
    /**
     * @var \NetSuite\Classes\SearchBooleanField
     */
    public $used;
    static $paramtypesmap = array(
        "code" => "SearchStringField",
        "dateSent" => "SearchDateField",
        "externalId" => "SearchMultiSelectField",
        "externalIdString" => "SearchStringField",
        "id" => "SearchLongField",
        "internalId" => "SearchMultiSelectField",
        "internalIdNumber" => "SearchLongField",
        "promotion" => "SearchMultiSelectField",
        "recipient" => "SearchMultiSelectField",
        "useCount" => "SearchLongField",
        "used" => "SearchBooleanField",
    );
}
