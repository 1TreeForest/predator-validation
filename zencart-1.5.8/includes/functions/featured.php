<?php

/*
 * featured-products functions
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Zen4All 2020 Sep 01 Modified in v1.5.8-alpha $
 */

/**
 * Set the status of a featured product
 * 
 * @global object $db
 * @param int $featured_id
 * @param int $status
 */
function zen_set_featured_status(int $featured_id, int $status)
{
  global $db;
  $sql = "UPDATE " . TABLE_FEATURED . "
          SET status = " . (int)$status . ",
              date_status_change = now()
          WHERE featured_id = " . (int)$featured_id;

  $db->Execute($sql);
}

/**
 * Auto expire products on featured
 * 
 * @global object $db
 */
function zen_expire_featured()
{
  global $db;

  $date_range = time();
  $zc_featured_date = date('Ymd', $date_range);

  $featured_query = "SELECT featured_id
                     FROM " . TABLE_FEATURED . "
                     WHERE status = 1
                     AND (
                       (" . $zc_featured_date . " >= expires_date
                         AND expires_date != '0001-01-01')
                       OR (" . $zc_featured_date . " < featured_date_available
                         AND featured_date_available != '0001-01-01'))";

  $featureds = $db->Execute($featured_query);

  if ($featureds->RecordCount() > 0) {
    foreach ($featureds as $featured) {
      zen_set_featured_status((int)$featured['featured_id'], 0);
    }
  }
}

/**
 * Auto start products on featured
 * 
 * @global object $db
 */
function zen_start_featured()
{
  global $db;

  $date_range = time();
  $zc_featured_date = date('Ymd', $date_range);

  $featured_query = "SELECT featured_id
                     FROM " . TABLE_FEATURED . "
                     WHERE status = 0
                     AND (((featured_date_available <= " . $zc_featured_date . " AND featured_date_available != '0001-01-01') AND (expires_date > " . $zc_featured_date . "))
                     OR ((featured_date_available <= " . $zc_featured_date . " AND featured_date_available != '0001-01-01') AND (expires_date = '0001-01-01'))
                     OR (featured_date_available = '0001-01-01' AND expires_date > " . $zc_featured_date . "))";

  $featureds_on = $db->Execute($featured_query);

  if ($featureds_on->RecordCount() > 0) {
    foreach ($featureds_on as $featured) {
      zen_set_featured_status((int)$featured['featured_id'], 1);
    }
  }

// turn off featured if not active yet
  $featured_query = "SELECT featured_id
                     FROM " . TABLE_FEATURED . "
                     WHERE status = 1
                     AND (" . $zc_featured_date . " < featured_date_available AND featured_date_available != '0001-01-01')";

  $featureds_off = $db->Execute($featured_query);

  if ($featureds_off->RecordCount() > 0) {
    foreach ($featureds_off as $featured) {
      zen_set_featured_status((int)$featured['featured_id'], 0);
    }
  }
}
