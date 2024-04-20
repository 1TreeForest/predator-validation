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

class ItemBinNumberSearchRowBasic extends SearchRowBasic {
    /**
     * @var \NetSuite\Classes\SearchColumnSelectField[]
     */
    public $binNumber;
    /**
     * @var \NetSuite\Classes\SearchColumnSelectField[]
     */
    public $location;
    /**
     * @var \NetSuite\Classes\SearchColumnDoubleField[]
     */
    public $quantityAvailable;
    /**
     * @var \NetSuite\Classes\SearchColumnDoubleField[]
     */
    public $quantityOnHand;
    static $paramtypesmap = array(
        "binNumber" => "SearchColumnSelectField[]",
        "location" => "SearchColumnSelectField[]",
        "quantityAvailable" => "SearchColumnDoubleField[]",
        "quantityOnHand" => "SearchColumnDoubleField[]",
    );
}
