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

class TransactionPaymentEventHoldReason {
    static $paramtypesmap = array(
    );
    const _amountExceedsMaximumAllowedAmount = "_amountExceedsMaximumAllowedAmount";
    const _authorizationDecline = "_authorizationDecline";
    const _cardExpired = "_cardExpired";
    const _cardInvalid = "_cardInvalid";
    const _confirmationOfTheOperationIsPending = "_confirmationOfTheOperationIsPending";
    const _externalFraudRejection = "_externalFraudRejection";
    const _externalFraudReview = "_externalFraudReview";
    const _failedToPrimeDevice = "_failedToPrimeDevice";
    const _fatalError = "_fatalError";
    const _forwardedToPayerAuthentication = "_forwardedToPayerAuthentication";
    const _forwardRequested = "_forwardRequested";
    const _forwardToAuthenticateDevice = "_forwardToAuthenticateDevice";
    const _forwardToChallengeShopper = "_forwardToChallengeShopper";
    const _gatewayAsynchronousNotification = "_gatewayAsynchronousNotification";
    const _gatewayError = "_gatewayError";
    const _generalHold = "_generalHold";
    const _generalReject = "_generalReject";
    const _notRequired = "_notRequired";
    const _operationWasSuccessful = "_operationWasSuccessful";
    const _operationWasTerminated = "_operationWasTerminated";
    const _overridenBy = "_overridenBy";
    const _partnerDecline = "_partnerDecline";
    const _paymentDeviceWasPrimed = "_paymentDeviceWasPrimed";
    const _paymentOperationWasCanceled = "_paymentOperationWasCanceled";
    const _systemError = "_systemError";
    const _verbalAuthorizationRequested = "_verbalAuthorizationRequested";
    const _verificationRejection = "_verificationRejection";
    const _verificationRequired = "_verificationRequired";
}
