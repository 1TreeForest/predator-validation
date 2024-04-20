<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: lat9 2022 May 12 Modified in v1.5.8-alpha $
 */

function zen_get_zcversion()
{
    return PROJECT_VERSION_MAJOR . '.' . PROJECT_VERSION_MINOR;
}

/**
 * Set timeout for the current script.
 * @param int $limit seconds
 */
function zen_set_time_limit($limit)
{
    @set_time_limit($limit);
}

/**
 * @param string $ip
 * @return boolean
 */
function zen_is_whitelisted_admin_ip($ip = null)
{
    if (empty($ip)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return strpos(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $ip) !== false;
}


////
// Wrapper function for round()
function zen_round($value, $precision)
{
    $value = round($value * pow(10, $precision), 0);
    $value = $value / pow(10, $precision);
    return $value;
}


/**
 * replacement for fmod to manage values < 1
 */
function fmod_round($x, $y)
{
    if ($y == 0) {
        return 0;
    }
    $x = (string)$x;
    $y = (string)$y;
    $zc_round = ($x * 1000) / ($y * 1000);
    $zc_round_ceil = round($zc_round, 0);
    $multiplier = $zc_round_ceil * $y;
    $results = abs(round($x - $multiplier, 6));
    return $results;
}


/**
 * Convert value to a float -- mainly used for sanitizing and returning non-empty strings or nulls
 * @param int|float|string $input
 * @return float|int
 */
function convertToFloat($input = 0)
{
    if ($input === null) return 0;
    $val = preg_replace('/[^0-9,\.\-]/', '', $input);
    // do a non-strict compare here:
    if ($val == 0) return 0;
    return (float)$val;
}


/**
 * function issetorArray
 *
 * returns an array[key] or default value if key does not exist
 *
 * @param array $array
 * @param $key
 * @param null $default
 * @return mixed
 */
function issetorArray(array $array, $key, $default = null)
{
    return isset($array[$key]) ? $array[$key] : $default;
}


/**
 * Get a shortened filename to fit within the db field constraints
 *
 * @param string $filename (could also be a URL)
 * @param string $table_name
 * @param string $field_name
 * @param string $extension String to denote the extension. The right-most "." is used as a fallback.
 * @return string
 */
function zen_limit_image_filename($filename, $table_name, $field_name, $extension = '.')
{
    if ($filename === 'none') return $filename;

    $max_length = zen_field_length($table_name, $field_name);
    $filename_length = function_exists('mb_strlen') ? mb_strlen($filename) : strlen($filename);

    if ($filename_length <= $max_length) return $filename;
    $divider_position = function_exists('mb_strrpos') ? mb_strrpos($filename, $extension) : strrpos($filename, $extension);
    $base = substr($filename, 0, $divider_position);
    $original_suffix = substr($filename, $divider_position);
    $suffix_length = function_exists('mb_strlen') ? mb_strlen($original_suffix) : strlen($original_suffix);
    $chop_length = $filename_length - $max_length;
    $shorter_length = $filename_length - $suffix_length - $chop_length;
    $shorter_base = substr($base, 0, $shorter_length);

    return $shorter_base . $original_suffix;
}


// function to return field type
// uses $tbl = table name, $fld = field name

function zen_field_type($tbl, $fld)
{
    global $db;
    $rs = $db->MetaColumns($tbl);
    $type = $rs[strtoupper($fld)]->type;
    return $type;
}


// function to return field length
// uses $tbl = table name, $fld = field name
function zen_field_length($tbl, $fld)
{
    global $db;
    $rs = $db->MetaColumns($tbl);
    $length = $rs[strtoupper($fld)]->max_length;
    return $length;
}


/**
 * Return all HTTP GET variables, except those passed as a parameter
 *
 * The return is a urlencoded string
 *
 * @param mixed $exclude_array either a single or array of parameter names to be excluded from output
 * @return string url_encoded string of GET params
 */
function zen_get_all_get_params($exclude_array = array())
{
    if (!is_array($exclude_array)) $exclude_array = array();
    $exclude_array = array_merge($exclude_array, array('main_page', 'error', 'x', 'y', 'cmd'));
    if (function_exists('zen_session_name')) {
        $exclude_array[] = zen_session_name();
    }
    $get_url = '';
    if (is_array($_GET) && (count($_GET) > 0)) {
        foreach ($_GET as $key => $value) {
            if (!in_array($key, $exclude_array)) {
                if (!is_array($value)) {
                    if (strlen($value) > 0) {
                        $get_url .= rawurlencode(stripslashes($key)) . '=' . rawurlencode(stripslashes($value)) . '&';
                    }
                } else {
                    if (IS_ADMIN_FLAG) continue; // admin (and maybe catalog?) doesn't support passing arrays by GET, so skipping any arrays here
                    foreach (array_filter($value) as $arr) {
                        if (is_array($arr)) continue;
                        $get_url .= rawurlencode(stripslashes($key)) . '[]=' . rawurlencode(stripslashes($arr)) . '&';
                    }
                }
            }
        }
    }

    $get_url = preg_replace('/&{2,}/', '&', $get_url);
    $get_url = preg_replace('/(&amp;)+/', '&amp;', $get_url);

    return $get_url;
}

/**
 * Return all GET params as (usually hidden) POST params
 * @param array $exclude_array GET keys to exclude from generated output
 * @param boolean $hidden generate hidden fields instead of regular input fields
 * @param string $parameters optional 'class="foo"' markup to include in non-hidden input fields
 * @return string HTML string of input fields
 */
function zen_post_all_get_params($exclude_array = array(), $hidden = true, $parameters = '')
{
    if (!is_array($exclude_array)) $exclude_array = array((string)$exclude_array);
    $exclude_array = array_merge($exclude_array, array('error', 'x', 'y'));
    if (function_exists('zen_session_name')) {
        $exclude_array[] = zen_session_name();
    }
    $fields = '';
    if (is_array($_GET) && (count($_GET) > 0)) {
        foreach ($_GET as $key => $value) {
            if (!in_array($key, $exclude_array)) {
                if (!is_array($value)) {
                    if (strlen($value) > 0) {
                        if ($hidden) {
                            $fields .= zen_draw_hidden_field($key, $value);
                        } else {
                            $fields .= zen_draw_input_field($key, $value, $parameters);
                        }
                    }
                } else {
                    foreach (array_filter($value) as $arr) {
                        if (is_array($arr)) continue;
                        if ($hidden) {
                            $fields .= zen_draw_hidden_field($key . '[]', $arr);
                        } else {
                            $fields .= zen_draw_input_field($key . '[]', $arr, $parameters);
                        }
                    }
                }
            }
        }
    }
    return $fields;
}


/**
 * Perform an array multisort, based on 1 or 2 columns being passed
 * (defaults to sorting by first column ascendingly then second column ascendingly unless otherwise specified)
 *
 * @param $data        multidimensional array to be sorted
 * @param $columnName1 string representing the named column to sort by as first criteria
 * @param $order1      either SORT_ASC or SORT_DESC (default SORT_ASC)
 * @param $columnName2 string representing named column as second criteria
 * @param $order2      either SORT_ASC or SORT_DESC (default SORT_ASC)
 * @return array   Original array sorted as specified
 */
function zen_sort_array($data, $columnName1 = '', $order1 = SORT_ASC, $columnName2 = '', $order2 = SORT_ASC)
{
    // simple validations
    $keys = array_keys($data);
    if ($columnName1 == '') {
        $columnName1 = $keys[0];
    }
    if (!in_array($order1, array(SORT_ASC, SORT_DESC))) $order1 = SORT_ASC;
    if ($columnName2 == '') {
        $columnName2 = $keys[1];
    }
    if (!in_array($order2, array(SORT_ASC, SORT_DESC))) $order2 = SORT_ASC;

    // prepare sub-arrays for aiding in sorting
    foreach ($data as $key => $val) {
        $sort1[] = $val[$columnName1];
        $sort2[] = $val[$columnName2];
    }
    // do actual sort based on specified fields.
    array_multisort($sort1, $order1, $sort2, $order2, $data);
    return $data;
}


/**
 * check to see if free shipping rules allow the specified shipping module to be enabled or to disable it in lieu of being free
 * @param $shipping_module
 * @return bool
 */
function zen_get_shipping_enabled(string $shipping_module): bool
{
    global $PHP_SELF;

    // for admin always true
    if (IS_ADMIN_FLAG && strstr($PHP_SELF, FILENAME_MODULES)) {
        return true;
    }

    $check_cart_free = $_SESSION['cart']->in_cart_check('product_is_always_free_shipping', '1');
    $check_cart_cnt = $_SESSION['cart']->count_contents();
    $check_cart_weight = $_SESSION['cart']->show_weight();

    // Free Shipping when 0 weight - enable freeshipper - ORDER_WEIGHT_ZERO_STATUS must be on
    if (ORDER_WEIGHT_ZERO_STATUS == '1' && ($check_cart_weight == 0 && $shipping_module == 'freeshipper')) {
        return true;
    }

    // Free Shipping when 0 weight - disable everyone - ORDER_WEIGHT_ZERO_STATUS must be on
    if (ORDER_WEIGHT_ZERO_STATUS == '1' && ($check_cart_weight == 0 && $shipping_module != 'freeshipper')) {
        return false;
    }

    if ($_SESSION['cart']->free_shipping_items() == $check_cart_cnt && $shipping_module == 'freeshipper') {
        return true;
    }

    if ($_SESSION['cart']->free_shipping_items() == $check_cart_cnt && $shipping_module != 'freeshipper') {
        return false;
    }

    // Always free shipping only true - enable freeshipper
    if ($check_cart_free == $check_cart_cnt && $shipping_module == 'freeshipper') {
        return true;
    }

    // Always free shipping only true - disable everyone
    if ($check_cart_free == $check_cart_cnt && $shipping_module != 'freeshipper') {
        return false;
    }

    // Always free shipping only is false - disable freeshipper
    if ($check_cart_free != $check_cart_cnt && $shipping_module == 'freeshipper') {
        return false;
    }
    return true;
}


/**
 * @param $from
 * @param $to
 * @param $string
 * @return string|string[]
 * @deprecated
 */
function zen_convert_linefeeds($from, $to, $string)
{
    trigger_error('Call to deprecated function zen_convert_linefeeds.', E_USER_DEPRECATED);

    return str_replace($from, $to, $string);
}

/**
 * Return a random value
 */
function zen_rand($min = null, $max = null)
{
    static $seeded;

    if (!isset($seeded)) {
        // -----
        // By default, microtime returns a string value.  To increase the precision of the
        // random seed, have it return a float to be multiplied and then convert the value
        // to an integer, as required by the mt_srand function.
        //
        mt_srand((int)(microtime(true) * 1000000));
        $seeded = true;
    }

    if (isset($min) && isset($max)) {
        if ($min >= $max) {
            return $min;
        } else {
            return mt_rand($min, $max);
        }
    } else {
        return mt_rand();
    }
}


// debug utility only
function utilDumpRequest($mode = 'p', $out = 'log')
{
    if ($mode == 'p') {
        $val = '<pre>DEBUG request: ' . print_r($_REQUEST, TRUE);
    } else {
        @ob_start();
        var_dump('DEBUG request: ', $_REQUEST);
        $val = @ob_get_contents();
        @ob_end_clean();
    }
    if ($out == 'log' || $out == 'l') {
        error_log($val);
    } else if ($out == 'die' || $out == 'd') {
        die($val);
    } else if ($out == 'echo' || $out == 'e') {
        echo $val;
    }
}

/**
 * this function will need to be removed if
 * we ever revert to a full laravel install
 */

function request()
{
    return \Zencart\Request\Request::getInstance();
}

function zen_updated_by_admin($admin_id = null)
{
    if (empty($admin_id)) {
        $admin_id = $_SESSION['admin_id'];
    }
    $name = zen_get_admin_name($admin_id);
    return ($name ?? 'Unknown Name') . " [$admin_id]";
}

/**
 * Lookup admin user name based on admin id
 * @param int $id
 * @return string
 */
function zen_get_admin_name($id = null)
{
    global $db;
    if (empty($id)) $id = $_SESSION['admin_id'];
    $sql = "SELECT admin_name FROM " . TABLE_ADMIN . " WHERE admin_id = :adminid: LIMIT 1";
    $sql = $db->bindVars($sql, ':adminid:', $id, 'integer');
    $result = $db->Execute($sql);
    return $result->RecordCount() ? $result->fields['admin_name'] : null;
}
