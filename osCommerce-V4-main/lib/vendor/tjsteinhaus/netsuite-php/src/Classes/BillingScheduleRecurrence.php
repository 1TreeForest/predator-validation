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

class BillingScheduleRecurrence {
    /**
     * @var integer
     */
    public $recurrenceId;
    /**
     * @var integer
     */
    public $count;
    /**
     * @var \NetSuite\Classes\BillingScheduleRecurrenceRecurrenceUnits
     */
    public $units;
    /**
     * @var boolean
     */
    public $relativeToPrevious;
    /**
     * @var string
     */
    public $recurrenceDate;
    /**
     * @var float
     */
    public $amount;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $paymentTerms;
    static $paramtypesmap = array(
        "recurrenceId" => "integer",
        "count" => "integer",
        "units" => "BillingScheduleRecurrenceRecurrenceUnits",
        "relativeToPrevious" => "boolean",
        "recurrenceDate" => "dateTime",
        "amount" => "float",
        "paymentTerms" => "RecordRef",
    );
}
