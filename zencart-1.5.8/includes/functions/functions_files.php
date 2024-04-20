<?php
/**
 * File functions
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: lat9 2022 May 05 New in v1.5.8-alpha $
 */


/**
 * build a list of directories in a specified parent folder
 * (formatted in id/text pairs for SELECT boxes)
 *
 * @param string $parent_folder
 * @param string $default_text
 * @return array (id/text pairs)
 *
 * @todo convert to a directory-iterator instead
 */
function zen_build_subdirectories_array($parent_folder = '', $default_text = 'Main Directory')
{
    if ($parent_folder == '') $parent_folder = DIR_FS_CATALOG_IMAGES;
    $dir_info = [];
    $dir_info[] = ['id' => '', 'text' => $default_text];

    $dir = @dir($parent_folder);
    if ($dir == null) return [];
    while ($file = $dir->read()) {
        if (is_dir($parent_folder . $file) && $file != "." && $file != "..") {
            $dir_info[] = ['id' => $file . '/', 'text' => $file];
        }
    }
    $dir->close();
    sort($dir_info);
    return $dir_info;
}


/**
 * find template or default file
 * @param string $check_directory
 * @param string $check_file
 * @param bool $dir_only
 * @return string
 */
function zen_get_file_directory($check_directory, $check_file, $dir_only = false)
{
    global $template_dir;

    $zv_filename = $check_file;
    if (strpos($zv_filename, '.php') === false) $zv_filename .= '.php';

    if (file_exists($check_directory . $template_dir . '/' . $zv_filename)) {
        $zv_directory = $check_directory . $template_dir . '/';
    } else {
        $zv_directory = $check_directory;
    }

    if ($dir_only === true) {
        return $zv_directory;
    }

    return $zv_directory . $zv_filename;
}

function zen_include_language_file($file, $folder, $page)
{
    global $messageStack, $languageLoader;
    if (IS_ADMIN_FLAG === true) {
        $lang_file = zen_get_file_directory(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $_SESSION['language'] . $folder, $file, 'false');
    } else {
        $lang_file = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . $folder, $file, 'false');
    }
    if ($languageLoader->hasLanguageFile(DIR_FS_CATALOG . DIR_WS_LANGUAGES,  $_SESSION['language'], $file, $folder)) {
        $languageLoader->loadExtraLanguageFiles(DIR_FS_CATALOG . DIR_WS_LANGUAGES,  $_SESSION['language'], $file, $folder);
    } else {
        if ($page === 'inline') {
?>
          <div class="messageStackCaution">
             <?php echo WARNING_COULD_NOT_LOCATE_LANG_FILE . $lang_file; ?> 
          </div>
<?php
        } else {
            if (is_object($messageStack)) {
                if (IS_ADMIN_FLAG === false) {
                    $messageStack->add($page, WARNING_COULD_NOT_LOCATE_LANG_FILE . $lang_file, 'caution');
                } else {
                    $messageStack->add_session(WARNING_COULD_NOT_LOCATE_LANG_FILE . $lang_file, 'caution');
                }
            }
        }
        return false; 
    }
    return true; 
}

/**
 * find module directory
 * include template specific immediate /modules files
 * new_products, products_new_listing, featured_products, featured_products_listing, product_listing, specials_index, upcoming,
 * products_all_listing, products_discount_prices, also_purchased_products
 * @param string $check_file
 * @param bool $dir_only
 * @return string
 */
function zen_get_module_directory($check_file, $dir_only = false)
{
    global $template_dir;

    $zv_filename = $check_file;
    if (strpos($zv_filename, '.php') === false) $zv_filename .= '.php';

    if (file_exists(DIR_WS_MODULES . $template_dir . '/' . $zv_filename)) {
        $template_dir_select = $template_dir . '/';
    } else {
        $template_dir_select = '';
    }

    if ($dir_only === true || $dir_only == 'true') {
        return $template_dir_select;
    }

    return $template_dir_select . $zv_filename;
}

/**
 * @param string $check_file
 * @return string
 */
function zen_get_module_sidebox_directory($check_file)
{
    global $template_dir;

    $zv_filename = $check_file;
    if (strpos($zv_filename, '.php') === false) $zv_filename .= '.php';

    if (file_exists(DIR_WS_MODULES . 'sideboxes/' . $template_dir . '/' . $zv_filename)) {
        $template_dir_select = 'sideboxes/' . $template_dir . '/';
    } else {
        $template_dir_select = 'sideboxes/';
    }

    return $template_dir_select . $zv_filename;
}


/**
 * Find index_filters directory
 * suitable for including template-specific immediate /modules files, such as:
 * new_products, products_new_listing, featured_products, featured_products_listing, product_listing, specials_index, upcoming,
 * products_all_listing, products_discount_prices, also_purchased_products
 * @param $check_file
 * @param bool $dir_only
 * @return false|mixed|string
 */
function zen_get_index_filters_directory($check_file, $dir_only = false)
{
    global $template_dir;
    $zv_filename = $check_file;
    if (strpos($zv_filename, '.php') === false) $zv_filename .= '.php';
    $checkArray = [];
    $checkArray[] = DIR_WS_INCLUDES . 'index_filters/' . $template_dir . '/' . $zv_filename;
    $checkArray[] = DIR_WS_INCLUDES . 'index_filters/' . $zv_filename;
    $checkArray[] = DIR_WS_INCLUDES . 'index_filters/' . $template_dir . '/' . 'default_filter.php';
    foreach ($checkArray as $key => $val) {
        if (file_exists($val)) {
            return ($dir_only === true || $dir_only == 'true') ? $val = substr($val, 0, strpos($val, '/')) : $val;
        }
    }
    return DIR_WS_INCLUDES . 'index_filters/' . 'default_filter.php';
}

/** @deprecated not used anywhere in core code */
function zen_get_file_permissions($mode)
{
// determine type
    if (($mode & 0xC000) == 0xC000) { // unix domain socket
        $type = 's';
    } elseif (($mode & 0x4000) == 0x4000) { // directory
        $type = 'd';
    } elseif (($mode & 0xA000) == 0xA000) { // symbolic link
        $type = 'l';
    } elseif (($mode & 0x8000) == 0x8000) { // regular file
        $type = '-';
    } elseif (($mode & 0x6000) == 0x6000) { //bBlock special file
        $type = 'b';
    } elseif (($mode & 0x2000) == 0x2000) { // character special file
        $type = 'c';
    } elseif (($mode & 0x1000) == 0x1000) { // named pipe
        $type = 'p';
    } else { // unknown
        $type = '?';
    }

// determine permissions
    $owner['read'] = ($mode & 00400) ? 'r' : '-';
    $owner['write'] = ($mode & 00200) ? 'w' : '-';
    $owner['execute'] = ($mode & 00100) ? 'x' : '-';
    $group['read'] = ($mode & 00040) ? 'r' : '-';
    $group['write'] = ($mode & 00020) ? 'w' : '-';
    $group['execute'] = ($mode & 00010) ? 'x' : '-';
    $world['read'] = ($mode & 00004) ? 'r' : '-';
    $world['write'] = ($mode & 00002) ? 'w' : '-';
    $world['execute'] = ($mode & 00001) ? 'x' : '-';

// adjust for SUID, SGID and sticky bit
    if ($mode & 0x800) $owner['execute'] = ($owner['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x400) $group['execute'] = ($group['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x200) $world['execute'] = ($world['execute'] == 'x') ? 't' : 'T';

    return $type .
        $owner['read'] . $owner['write'] . $owner['execute'] .
        $group['read'] . $group['write'] . $group['execute'] .
        $world['read'] . $world['write'] . $world['execute'];
}

/**
 * delete a file
 *
 * @TODO - refactor to bypass the use of the global $zen_remove_error and use a return value instead
 * @TODO - and give it a more meaningful name at the same time
 *
 * @param string $source
 */
function zen_remove($source)
{
    global $messageStack, $zen_remove_error;

    $zen_remove_error = false;

    if (is_dir($source)) {
        $dir = dir($source);
        while ($file = $dir->read()) {
            if (($file != '.') && ($file != '..')) {
                if (is_writeable($source . '/' . $file)) {
                    zen_remove($source . '/' . $file);
                } else {
                    $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source . '/' . $file), 'error');
                    $zen_remove_error = true;
                }
            }
        }
        $dir->close();

        if (is_writeable($source)) {
            rmdir($source);
            zen_record_admin_activity('Removed directory from server: [' . $source . ']', 'notice');
        } else {
            $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_REMOVEABLE, $source), 'error');
            $zen_remove_error = true;
        }
    } else {
        if (is_writeable($source)) {
            unlink($source);
            zen_record_admin_activity('Deleted file from server: [' . $source . ']', 'notice');
        } else {
            $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source), 'error');
            $zen_remove_error = true;
        }
    }
}

/**
 * attempt to make the specified file read-only
 *
 * @return boolean
 * @var string
 */
function set_unwritable($filepath)
{
    return @chmod($filepath, 0444);
}


/**
 * function to override PHP's is_writable() which can occasionally be unreliable due to O/S and F/S differences
 * attempts to open the specified file for writing. Returns true if successful, false if not.
 * if a directory is specified, uses PHP's is_writable() anyway
 *
 * @param string $filepath
 * @param bool $make_unwritable
 * @return boolean
 */
function is__writeable($filepath, $make_unwritable = true)
{
    if (is_dir($filepath)) return is_writable($filepath);
    $fp = @fopen($filepath, 'a');
    if ($fp) {
        @fclose($fp);
        if ($make_unwritable) set_unwritable($filepath);
        $fp = @fopen($filepath, 'a');
        if ($fp) {
            @fclose($fp);
            return true;
        }
    }
    return false;
}


/**
 * @TODO - refactor where this is used, to find a better way of displaying whatever is needed
 * @param string $filename
 * @return string
 */
function zen_get_uploaded_file(string $filename)
{
    $parts = explode(". ", $filename, 2);
    $filenum = $parts[0];
    $filename = $parts[1];
    $file_parts = explode(".", $filename, 2);
    $filetype = $file_parts[count($file_parts) - 1];
    return $filenum . "." . $filetype;
}


/**
 * Obtain a list of .log/.xml files from the /logs/ folder
 * (and also /cache/ folder for backward compatibility of older modules which store logs there)
 *
 * If $maxToList == 'count' then it returns the total number of files found
 * If an integer is passed, then an array of files is returned, including paths, filenames, and datetime details
 *
 * @param string|int $maxToList (integer or 'count')
 * @return array|int
 *
 * inspired by log checking suggestion from Steve Sherratt (torvista)
 */
function get_logs_data($maxToList = 'count')
{
    global $zcDate;

    if (!defined('DIR_FS_LOGS')) define('DIR_FS_LOGS', DIR_FS_CATALOG . 'logs');
    if (!defined('DIR_FS_SQL_CACHE')) define('DIR_FS_SQL_CACHE', DIR_FS_CATALOG . 'cache');
    $logs = array();
    $file = array();
    $i = 0;
    foreach (array(DIR_FS_LOGS, DIR_FS_SQL_CACHE) as $purgeFolder) {
        $purgeFolder = rtrim($purgeFolder, '/');
        if (!file_exists($purgeFolder) || !is_dir($purgeFolder)) continue;

        $dir = dir($purgeFolder);
        while ($logfile = $dir->read()) {
            if (substr($logfile, 0, 1) == '.') continue;
            if (!preg_match('/.*(\.log|\.xml)$/', $logfile)) continue; // xml allows for usps debug

            if ($maxToList != 'count') {
                $filename = $purgeFolder . '/' . $logfile;
                $logs[$i]['path'] = $purgeFolder . "/";
                $logs[$i]['filename'] = $logfile;
                $logs[$i]['filesize'] = @filesize($filename);
                $logs[$i]['unixtime'] = @filemtime($filename);
                $logs[$i]['datetime'] = $zcDate->output(DATE_TIME_FORMAT, $logs[$i]['unixtime']);
            }
            $i++;
            if ($maxToList != 'count' && $i >= $maxToList) break;
        }
        $dir->close();
        unset($dir);
    }

    if ($maxToList == 'count') return $i;

    $logs = zen_sort_array($logs, 'unixtime', SORT_DESC);
    return $logs;
}


