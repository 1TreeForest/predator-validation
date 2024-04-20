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

class VendorReturnAuthorizationOrderStatus {
    static $paramtypesmap = array(
    );
    const _cancelled = "_cancelled";
    const _closed = "_closed";
    const _credited = "_credited";
    const _partiallyReturned = "_partiallyReturned";
    const _pendingApproval = "_pendingApproval";
    const _pendingCredit = "_pendingCredit";
    const _pendingCreditPartiallyReturned = "_pendingCreditPartiallyReturned";
    const _pendingReturn = "_pendingReturn";
    const _undefined = "_undefined";
}
