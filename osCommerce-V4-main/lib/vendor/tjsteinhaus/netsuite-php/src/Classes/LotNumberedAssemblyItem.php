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

class LotNumberedAssemblyItem extends Record {
    /**
     * @var string
     */
    public $createdDate;
    /**
     * @var string
     */
    public $lastModifiedDate;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $customForm;
    /**
     * @var string
     */
    public $itemId;
    /**
     * @var string
     */
    public $upcCode;
    /**
     * @var string
     */
    public $displayName;
    /**
     * @var string
     */
    public $vendorName;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $parent;
    /**
     * @var boolean
     */
    public $printItems;
    /**
     * @var boolean
     */
    public $isOnline;
    /**
     * @var boolean
     */
    public $isGcoCompliant;
    /**
     * @var boolean
     */
    public $offerSupport;
    /**
     * @var boolean
     */
    public $useComponentYield;
    /**
     * @var boolean
     */
    public $isInactive;
    /**
     * @var boolean
     */
    public $availableToPartners;
    /**
     * @var \NetSuite\Classes\RecordRefList
     */
    public $subsidiaryList;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $department;
    /**
     * @var boolean
     */
    public $includeChildren;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $class;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $location;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $costCategory;
    /**
     * @var string
     */
    public $description;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $cogsAccount;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $vendor;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $incomeAccount;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $intercoIncomeAccount;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $assetAccount;
    /**
     * @var boolean
     */
    public $matchBillToReceipt;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $billQtyVarianceAcct;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $billPriceVarianceAcct;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $billExchRateVarianceAcct;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $gainLossAccount;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $salesTaxCode;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $purchaseTaxCode;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $prodQtyVarianceAcct;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $prodPriceVarianceAcct;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $purchasePriceVarianceAcct;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $wipVarianceAcct;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $taxSchedule;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $scrapAcct;
    /**
     * @var float
     */
    public $shippingCost;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $wipAcct;
    /**
     * @var float
     */
    public $handlingCost;
    /**
     * @var float
     */
    public $weight;
    /**
     * @var \NetSuite\Classes\ItemWeightUnit
     */
    public $weightUnit;
    /**
     * @var \NetSuite\Classes\ItemCostingMethod
     */
    public $costingMethod;
    /**
     * @var boolean
     */
    public $isTaxable;
    /**
     * @var string
     */
    public $costingMethodDisplay;
    /**
     * @var float
     */
    public $rate;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $unitsType;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $stockUnit;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $purchaseUnit;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $saleUnit;
    /**
     * @var boolean
     */
    public $trackLandedCost;
    /**
     * @var string
     */
    public $matrixItemNameTemplate;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $billingSchedule;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $deferredRevenueAccount;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $revRecSchedule;
    /**
     * @var string
     */
    public $stockDescription;
    /**
     * @var boolean
     */
    public $producer;
    /**
     * @var string
     */
    public $manufacturer;
    /**
     * @var string
     */
    public $mpn;
    /**
     * @var boolean
     */
    public $multManufactureAddr;
    /**
     * @var string
     */
    public $manufactureraddr1;
    /**
     * @var string
     */
    public $manufacturerCity;
    /**
     * @var string
     */
    public $manufacturerState;
    /**
     * @var string
     */
    public $manufacturerZip;
    /**
     * @var \NetSuite\Classes\Country::*
     */
    public $countryOfManufacture;
    /**
     * @var \NetSuite\Classes\AssemblyItemEffectiveBomControl
     */
    public $effectiveBomControl;
    /**
     * @var string
     */
    public $manufacturerTaxId;
    /**
     * @var string
     */
    public $defaultRevision;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $defaultItemShipMethod;
    /**
     * @var \NetSuite\Classes\ShippingCarrier
     */
    public $itemCarrier;
    /**
     * @var boolean
     */
    public $roundUpAsComponent;
    /**
     * @var float
     */
    public $purchaseOrderQuantity;
    /**
     * @var float
     */
    public $purchaseOrderAmount;
    /**
     * @var float
     */
    public $purchaseOrderQuantityDiff;
    /**
     * @var float
     */
    public $receiptQuantity;
    /**
     * @var float
     */
    public $receiptAmount;
    /**
     * @var float
     */
    public $receiptQuantityDiff;
    /**
     * @var boolean
     */
    public $isDropShipItem;
    /**
     * @var boolean
     */
    public $isPhantom;
    /**
     * @var \NetSuite\Classes\RecordRefList
     */
    public $itemShipMethodList;
    /**
     * @var string
     */
    public $scheduleBNumber;
    /**
     * @var integer
     */
    public $scheduleBQuantity;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $scheduleBCode;
    /**
     * @var string
     */
    public $manufacturerTariff;
    /**
     * @var \NetSuite\Classes\ItemPreferenceCriterion
     */
    public $preferenceCriterion;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $issueProduct;
    /**
     * @var integer
     */
    public $minimumQuantity;
    /**
     * @var boolean
     */
    public $enforceMinQtyInternally;
    /**
     * @var integer
     */
    public $maximumQuantity;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $softDescriptor;
    /**
     * @var boolean
     */
    public $isSpecialWorkOrderItem;
    /**
     * @var boolean
     */
    public $pricesIncludeTax;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $quantityPricingSchedule;
    /**
     * @var boolean
     */
    public $buildEntireAssembly;
    /**
     * @var float
     */
    public $quantityOnHand;
    /**
     * @var boolean
     */
    public $useMarginalRates;
    /**
     * @var integer
     */
    public $reorderMultiple;
    /**
     * @var float
     */
    public $cost;
    /**
     * @var \NetSuite\Classes\ItemCostEstimateType
     */
    public $costEstimateType;
    /**
     * @var boolean
     */
    public $isHazmatItem;
    /**
     * @var string
     */
    public $hazmatId;
    /**
     * @var string
     */
    public $hazmatShippingName;
    /**
     * @var string
     */
    public $hazmatHazardClass;
    /**
     * @var \NetSuite\Classes\HazmatPackingGroup
     */
    public $hazmatPackingGroup;
    /**
     * @var string
     */
    public $hazmatItemUnits;
    /**
     * @var float
     */
    public $hazmatItemUnitsQty;
    /**
     * @var float
     */
    public $costEstimate;
    /**
     * @var float
     */
    public $transferPrice;
    /**
     * @var \NetSuite\Classes\ItemOverallQuantityPricingType
     */
    public $overallQuantityPricingType;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $pricingGroup;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $intercoCogsAccount;
    /**
     * @var float
     */
    public $vsoePrice;
    /**
     * @var \NetSuite\Classes\VsoeSopGroup
     */
    public $vsoeSopGroup;
    /**
     * @var \NetSuite\Classes\VsoeDeferral
     */
    public $vsoeDeferral;
    /**
     * @var \NetSuite\Classes\VsoePermitDiscount
     */
    public $vsoePermitDiscount;
    /**
     * @var boolean
     */
    public $vsoeDelivered;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $itemRevenueCategory;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $unbuildVarianceAccount;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $revenueRecognitionRule;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $revRecForecastRule;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $revenueAllocationGroup;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $createRevenuePlansOn;
    /**
     * @var boolean
     */
    public $directRevenuePosting;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $dropshipExpenseAccount;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $preferredLocation;
    /**
     * @var boolean
     */
    public $isStorePickupAllowed;
    /**
     * @var float
     */
    public $totalValue;
    /**
     * @var boolean
     */
    public $useBins;
    /**
     * @var float
     */
    public $averageCost;
    /**
     * @var float
     */
    public $lastPurchasePrice;
    /**
     * @var string
     */
    public $purchaseDescription;
    /**
     * @var integer
     */
    public $leadTime;
    /**
     * @var boolean
     */
    public $autoLeadTime;
    /**
     * @var integer
     */
    public $buildTime;
    /**
     * @var float
     */
    public $safetyStockLevel;
    /**
     * @var integer
     */
    public $safetyStockLevelDays;
    /**
     * @var boolean
     */
    public $seasonalDemand;
    /**
     * @var string
     */
    public $serialNumbers;
    /**
     * @var float
     */
    public $reorderPoint;
    /**
     * @var float
     */
    public $preferredStockLevel;
    /**
     * @var string
     */
    public $expirationDate;
    /**
     * @var string
     */
    public $lastInvtCountDate;
    /**
     * @var string
     */
    public $nextInvtCountDate;
    /**
     * @var integer
     */
    public $invtCountInterval;
    /**
     * @var \NetSuite\Classes\ItemInvtClassification
     */
    public $invtClassification;
    /**
     * @var float
     */
    public $demandModifier;
    /**
     * @var boolean
     */
    public $autoReorderPoint;
    /**
     * @var boolean
     */
    public $autoPreferredStockLevel;
    /**
     * @var float
     */
    public $preferredStockLevelDays;
    /**
     * @var boolean
     */
    public $shipIndividually;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $shipPackage;
    /**
     * @var float
     */
    public $defaultReturnCost;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $supplyReplenishmentMethod;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $alternateDemandSourceItem;
    /**
     * @var float
     */
    public $fixedLotSize;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $supplyType;
    /**
     * @var integer
     */
    public $demandTimeFence;
    /**
     * @var integer
     */
    public $supplyTimeFence;
    /**
     * @var integer
     */
    public $rescheduleInDays;
    /**
     * @var integer
     */
    public $rescheduleOutDays;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $supplyLotSizingMethod;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $demandSource;
    /**
     * @var float
     */
    public $quantityCommitted;
    /**
     * @var float
     */
    public $quantityAvailable;
    /**
     * @var float
     */
    public $quantityBackOrdered;
    /**
     * @var float
     */
    public $quantityOnOrder;
    /**
     * @var string
     */
    public $storeDisplayName;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $storeDisplayThumbnail;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $storeDisplayImage;
    /**
     * @var string
     */
    public $storeDescription;
    /**
     * @var string
     */
    public $storeDetailedDescription;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $storeItemTemplate;
    /**
     * @var string
     */
    public $pageTitle;
    /**
     * @var string
     */
    public $metaTagHtml;
    /**
     * @var boolean
     */
    public $excludeFromSitemap;
    /**
     * @var \NetSuite\Classes\SitemapPriority
     */
    public $sitemapPriority;
    /**
     * @var string
     */
    public $urlComponent;
    /**
     * @var string
     */
    public $searchKeywords;
    /**
     * @var boolean
     */
    public $isDonationItem;
    /**
     * @var boolean
     */
    public $showDefaultDonationAmount;
    /**
     * @var float
     */
    public $maxDonationAmount;
    /**
     * @var boolean
     */
    public $dontShowPrice;
    /**
     * @var string
     */
    public $noPriceMessage;
    /**
     * @var string
     */
    public $outOfStockMessage;
    /**
     * @var string
     */
    public $shoppingDotComCategory;
    /**
     * @var \NetSuite\Classes\ItemOutOfStockBehavior
     */
    public $outOfStockBehavior;
    /**
     * @var integer
     */
    public $shopzillaCategoryId;
    /**
     * @var string
     */
    public $nexTagCategory;
    /**
     * @var \NetSuite\Classes\ProductFeedList
     */
    public $productFeedList;
    /**
     * @var string
     */
    public $relatedItemsDescription;
    /**
     * @var boolean
     */
    public $onSpecial;
    /**
     * @var string
     */
    public $specialsDescription;
    /**
     * @var string
     */
    public $featuredDescription;
    /**
     * @var \NetSuite\Classes\ItemOptionsList
     */
    public $itemOptionsList;
    /**
     * @var \NetSuite\Classes\RecordRefList
     */
    public $itemNumberOptionsList;
    /**
     * @var \NetSuite\Classes\ItemVendorList
     */
    public $itemVendorList;
    /**
     * @var \NetSuite\Classes\PricingMatrix
     */
    public $pricingMatrix;
    /**
     * @var \NetSuite\Classes\LotNumberedAssemblyItemBillOfMaterialsList
     */
    public $billOfMaterialsList;
    /**
     * @var \NetSuite\Classes\ItemMemberList
     */
    public $memberList;
    /**
     * @var \NetSuite\Classes\ItemAccountingBookDetailList
     */
    public $accountingBookDetailList;
    /**
     * @var \NetSuite\Classes\LotNumberedInventoryItemLocationsList
     */
    public $locationsList;
    /**
     * @var \NetSuite\Classes\SiteCategoryList
     */
    public $siteCategoryList;
    /**
     * @var \NetSuite\Classes\InventoryItemBinNumberList
     */
    public $binNumberList;
    /**
     * @var \NetSuite\Classes\LotNumberedInventoryItemNumbersList
     */
    public $numbersList;
    /**
     * @var \NetSuite\Classes\TranslationList
     */
    public $translationsList;
    /**
     * @var \NetSuite\Classes\PresentationItemList
     */
    public $presentationItemList;
    /**
     * @var \NetSuite\Classes\LotNumberedAssemblyItemHierarchyVersionsList
     */
    public $hierarchyVersionsList;
    /**
     * @var integer
     */
    public $futureHorizon;
    /**
     * @var \NetSuite\Classes\RecordRef
     */
    public $consumptionUnit;
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
        "createdDate" => "dateTime",
        "lastModifiedDate" => "dateTime",
        "customForm" => "RecordRef",
        "itemId" => "string",
        "upcCode" => "string",
        "displayName" => "string",
        "vendorName" => "string",
        "parent" => "RecordRef",
        "printItems" => "boolean",
        "isOnline" => "boolean",
        "isGcoCompliant" => "boolean",
        "offerSupport" => "boolean",
        "useComponentYield" => "boolean",
        "isInactive" => "boolean",
        "availableToPartners" => "boolean",
        "subsidiaryList" => "RecordRefList",
        "department" => "RecordRef",
        "includeChildren" => "boolean",
        "class" => "RecordRef",
        "location" => "RecordRef",
        "costCategory" => "RecordRef",
        "description" => "string",
        "cogsAccount" => "RecordRef",
        "vendor" => "RecordRef",
        "incomeAccount" => "RecordRef",
        "intercoIncomeAccount" => "RecordRef",
        "assetAccount" => "RecordRef",
        "matchBillToReceipt" => "boolean",
        "billQtyVarianceAcct" => "RecordRef",
        "billPriceVarianceAcct" => "RecordRef",
        "billExchRateVarianceAcct" => "RecordRef",
        "gainLossAccount" => "RecordRef",
        "salesTaxCode" => "RecordRef",
        "purchaseTaxCode" => "RecordRef",
        "prodQtyVarianceAcct" => "RecordRef",
        "prodPriceVarianceAcct" => "RecordRef",
        "purchasePriceVarianceAcct" => "RecordRef",
        "wipVarianceAcct" => "RecordRef",
        "taxSchedule" => "RecordRef",
        "scrapAcct" => "RecordRef",
        "shippingCost" => "float",
        "wipAcct" => "RecordRef",
        "handlingCost" => "float",
        "weight" => "float",
        "weightUnit" => "ItemWeightUnit",
        "costingMethod" => "ItemCostingMethod",
        "isTaxable" => "boolean",
        "costingMethodDisplay" => "string",
        "rate" => "float",
        "unitsType" => "RecordRef",
        "stockUnit" => "RecordRef",
        "purchaseUnit" => "RecordRef",
        "saleUnit" => "RecordRef",
        "trackLandedCost" => "boolean",
        "matrixItemNameTemplate" => "string",
        "billingSchedule" => "RecordRef",
        "deferredRevenueAccount" => "RecordRef",
        "revRecSchedule" => "RecordRef",
        "stockDescription" => "string",
        "producer" => "boolean",
        "manufacturer" => "string",
        "mpn" => "string",
        "multManufactureAddr" => "boolean",
        "manufactureraddr1" => "string",
        "manufacturerCity" => "string",
        "manufacturerState" => "string",
        "manufacturerZip" => "string",
        "countryOfManufacture" => "Country",
        "effectiveBomControl" => "AssemblyItemEffectiveBomControl",
        "manufacturerTaxId" => "string",
        "defaultRevision" => "string",
        "defaultItemShipMethod" => "RecordRef",
        "itemCarrier" => "ShippingCarrier",
        "roundUpAsComponent" => "boolean",
        "purchaseOrderQuantity" => "float",
        "purchaseOrderAmount" => "float",
        "purchaseOrderQuantityDiff" => "float",
        "receiptQuantity" => "float",
        "receiptAmount" => "float",
        "receiptQuantityDiff" => "float",
        "isDropShipItem" => "boolean",
        "isPhantom" => "boolean",
        "itemShipMethodList" => "RecordRefList",
        "scheduleBNumber" => "string",
        "scheduleBQuantity" => "integer",
        "scheduleBCode" => "RecordRef",
        "manufacturerTariff" => "string",
        "preferenceCriterion" => "ItemPreferenceCriterion",
        "issueProduct" => "RecordRef",
        "minimumQuantity" => "integer",
        "enforceMinQtyInternally" => "boolean",
        "maximumQuantity" => "integer",
        "softDescriptor" => "RecordRef",
        "isSpecialWorkOrderItem" => "boolean",
        "pricesIncludeTax" => "boolean",
        "quantityPricingSchedule" => "RecordRef",
        "buildEntireAssembly" => "boolean",
        "quantityOnHand" => "float",
        "useMarginalRates" => "boolean",
        "reorderMultiple" => "integer",
        "cost" => "float",
        "costEstimateType" => "ItemCostEstimateType",
        "isHazmatItem" => "boolean",
        "hazmatId" => "string",
        "hazmatShippingName" => "string",
        "hazmatHazardClass" => "string",
        "hazmatPackingGroup" => "HazmatPackingGroup",
        "hazmatItemUnits" => "string",
        "hazmatItemUnitsQty" => "float",
        "costEstimate" => "float",
        "transferPrice" => "float",
        "overallQuantityPricingType" => "ItemOverallQuantityPricingType",
        "pricingGroup" => "RecordRef",
        "intercoCogsAccount" => "RecordRef",
        "vsoePrice" => "float",
        "vsoeSopGroup" => "VsoeSopGroup",
        "vsoeDeferral" => "VsoeDeferral",
        "vsoePermitDiscount" => "VsoePermitDiscount",
        "vsoeDelivered" => "boolean",
        "itemRevenueCategory" => "RecordRef",
        "unbuildVarianceAccount" => "RecordRef",
        "revenueRecognitionRule" => "RecordRef",
        "revRecForecastRule" => "RecordRef",
        "revenueAllocationGroup" => "RecordRef",
        "createRevenuePlansOn" => "RecordRef",
        "directRevenuePosting" => "boolean",
        "dropshipExpenseAccount" => "RecordRef",
        "preferredLocation" => "RecordRef",
        "isStorePickupAllowed" => "boolean",
        "totalValue" => "float",
        "useBins" => "boolean",
        "averageCost" => "float",
        "lastPurchasePrice" => "float",
        "purchaseDescription" => "string",
        "leadTime" => "integer",
        "autoLeadTime" => "boolean",
        "buildTime" => "integer",
        "safetyStockLevel" => "float",
        "safetyStockLevelDays" => "integer",
        "seasonalDemand" => "boolean",
        "serialNumbers" => "string",
        "reorderPoint" => "float",
        "preferredStockLevel" => "float",
        "expirationDate" => "dateTime",
        "lastInvtCountDate" => "dateTime",
        "nextInvtCountDate" => "dateTime",
        "invtCountInterval" => "integer",
        "invtClassification" => "ItemInvtClassification",
        "demandModifier" => "float",
        "autoReorderPoint" => "boolean",
        "autoPreferredStockLevel" => "boolean",
        "preferredStockLevelDays" => "float",
        "shipIndividually" => "boolean",
        "shipPackage" => "RecordRef",
        "defaultReturnCost" => "float",
        "supplyReplenishmentMethod" => "RecordRef",
        "alternateDemandSourceItem" => "RecordRef",
        "fixedLotSize" => "float",
        "supplyType" => "RecordRef",
        "demandTimeFence" => "integer",
        "supplyTimeFence" => "integer",
        "rescheduleInDays" => "integer",
        "rescheduleOutDays" => "integer",
        "supplyLotSizingMethod" => "RecordRef",
        "demandSource" => "RecordRef",
        "quantityCommitted" => "float",
        "quantityAvailable" => "float",
        "quantityBackOrdered" => "float",
        "quantityOnOrder" => "float",
        "storeDisplayName" => "string",
        "storeDisplayThumbnail" => "RecordRef",
        "storeDisplayImage" => "RecordRef",
        "storeDescription" => "string",
        "storeDetailedDescription" => "string",
        "storeItemTemplate" => "RecordRef",
        "pageTitle" => "string",
        "metaTagHtml" => "string",
        "excludeFromSitemap" => "boolean",
        "sitemapPriority" => "SitemapPriority",
        "urlComponent" => "string",
        "searchKeywords" => "string",
        "isDonationItem" => "boolean",
        "showDefaultDonationAmount" => "boolean",
        "maxDonationAmount" => "float",
        "dontShowPrice" => "boolean",
        "noPriceMessage" => "string",
        "outOfStockMessage" => "string",
        "shoppingDotComCategory" => "string",
        "outOfStockBehavior" => "ItemOutOfStockBehavior",
        "shopzillaCategoryId" => "integer",
        "nexTagCategory" => "string",
        "productFeedList" => "ProductFeedList",
        "relatedItemsDescription" => "string",
        "onSpecial" => "boolean",
        "specialsDescription" => "string",
        "featuredDescription" => "string",
        "itemOptionsList" => "ItemOptionsList",
        "itemNumberOptionsList" => "RecordRefList",
        "itemVendorList" => "ItemVendorList",
        "pricingMatrix" => "PricingMatrix",
        "billOfMaterialsList" => "LotNumberedAssemblyItemBillOfMaterialsList",
        "memberList" => "ItemMemberList",
        "accountingBookDetailList" => "ItemAccountingBookDetailList",
        "locationsList" => "LotNumberedInventoryItemLocationsList",
        "siteCategoryList" => "SiteCategoryList",
        "binNumberList" => "InventoryItemBinNumberList",
        "numbersList" => "LotNumberedInventoryItemNumbersList",
        "translationsList" => "TranslationList",
        "presentationItemList" => "PresentationItemList",
        "hierarchyVersionsList" => "LotNumberedAssemblyItemHierarchyVersionsList",
        "futureHorizon" => "integer",
        "consumptionUnit" => "RecordRef",
        "customFieldList" => "CustomFieldList",
        "internalId" => "string",
        "externalId" => "string",
    );
}
