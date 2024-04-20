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

class ResourceAllocationSearchRow extends SearchRow {
    /**
     * @var \NetSuite\Classes\ResourceAllocationSearchRowBasic
     */
    public $basic;
    /**
     * @var \NetSuite\Classes\CustomerSearchRowBasic
     */
    public $customerJoin;
    /**
     * @var \NetSuite\Classes\EmployeeSearchRowBasic
     */
    public $employeeJoin;
    /**
     * @var \NetSuite\Classes\JobSearchRowBasic
     */
    public $jobJoin;
    /**
     * @var \NetSuite\Classes\ProjectTaskSearchRowBasic
     */
    public $projectTaskJoin;
    /**
     * @var \NetSuite\Classes\EntitySearchRowBasic
     */
    public $requestedByJoin;
    /**
     * @var \NetSuite\Classes\EntitySearchRowBasic
     */
    public $resourceJoin;
    /**
     * @var \NetSuite\Classes\EmployeeSearchRowBasic
     */
    public $userJoin;
    /**
     * @var \NetSuite\Classes\VendorSearchRowBasic
     */
    public $vendorJoin;
    /**
     * @var \NetSuite\Classes\CustomSearchRowBasic[]
     */
    public $customSearchJoin;
    static $paramtypesmap = array(
        "basic" => "ResourceAllocationSearchRowBasic",
        "customerJoin" => "CustomerSearchRowBasic",
        "employeeJoin" => "EmployeeSearchRowBasic",
        "jobJoin" => "JobSearchRowBasic",
        "projectTaskJoin" => "ProjectTaskSearchRowBasic",
        "requestedByJoin" => "EntitySearchRowBasic",
        "resourceJoin" => "EntitySearchRowBasic",
        "userJoin" => "EmployeeSearchRowBasic",
        "vendorJoin" => "VendorSearchRowBasic",
        "customSearchJoin" => "CustomSearchRowBasic[]",
    );
}
