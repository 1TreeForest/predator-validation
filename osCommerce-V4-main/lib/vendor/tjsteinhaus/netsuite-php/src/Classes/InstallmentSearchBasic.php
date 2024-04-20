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

class InstallmentSearchBasic extends SearchRecordBasic {
    /**
     * @var \NetSuite\Classes\SearchDoubleField
     */
    public $amount;
    /**
     * @var \NetSuite\Classes\SearchDoubleField
     */
    public $amountPaid;
    /**
     * @var \NetSuite\Classes\SearchDoubleField
     */
    public $amountRemaining;
    /**
     * @var \NetSuite\Classes\SearchLongField
     */
    public $daysOverdue;
    /**
     * @var \NetSuite\Classes\SearchDateField
     */
    public $dueDate;
    /**
     * @var \NetSuite\Classes\SearchDoubleField
     */
    public $fxAmount;
    /**
     * @var \NetSuite\Classes\SearchDoubleField
     */
    public $fxAmountPaid;
    /**
     * @var \NetSuite\Classes\SearchDoubleField
     */
    public $fxAmountRemaining;
    /**
     * @var \NetSuite\Classes\SearchLongField
     */
    public $installmentNumber;
    /**
     * @var \NetSuite\Classes\SearchCustomFieldList
     */
    public $customFieldList;
    static $paramtypesmap = array(
        "amount" => "SearchDoubleField",
        "amountPaid" => "SearchDoubleField",
        "amountRemaining" => "SearchDoubleField",
        "daysOverdue" => "SearchLongField",
        "dueDate" => "SearchDateField",
        "fxAmount" => "SearchDoubleField",
        "fxAmountPaid" => "SearchDoubleField",
        "fxAmountRemaining" => "SearchDoubleField",
        "installmentNumber" => "SearchLongField",
        "customFieldList" => "SearchCustomFieldList",
    );
}
