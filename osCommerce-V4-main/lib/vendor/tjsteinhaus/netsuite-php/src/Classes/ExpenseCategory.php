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

class ExpenseCategory extends Record {
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $customForm;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $expenseAcct;
    /**
     * @var boolean
     */
    public $isInactive;
    /**
     * @var boolean
     */
    public $rateRequired;
    /**
     * @var \NetSuite\Classes\RecordRefList
     */
    public $subsidiaryList;
    /**
     * @var float
     */
    public $defaultRate;
    /**
     * @var \NetSuite\Classes\ExpenseCategoryRatesList
     */
    public $ratesList;
    /**
     * @var \NetSuite\Classes\TranslationList
     */
    public $translationsList;
    /**
     * @var \NetSuite\Classes\CustomFieldList
     */
    public $customFieldList;
    /**
     * @var string
     */
    public $internalId;
    /**
     * @var string
     */
    public $externalId;
    static $paramtypesmap = array(
        "customForm" => "RecordRef",
        "name" => "string",
        "description" => "string",
        "expenseAcct" => "RecordRef",
        "isInactive" => "boolean",
        "rateRequired" => "boolean",
        "subsidiaryList" => "RecordRefList",
        "defaultRate" => "float",
        "ratesList" => "ExpenseCategoryRatesList",
        "translationsList" => "TranslationList",
        "customFieldList" => "CustomFieldList",
        "internalId" => "string",
        "externalId" => "string",
    );
}
