<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: includes/defender/mimecheck.php
| Author: Core Development Team
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
namespace Defender;

/**
 * Class Mimecheck
 * Check file types of the uploaded file with known mime types list to
 * prevent uploading unwanted files if enabled. This feature is to detect payload
 * in image extensions that has been partly encoded.
 */
class ImageValidation {

    /**
     * Check extensions only. This will not check against mimetype header
     */
    public static function validateExtensions() {
        if (fusion_get_settings('mime_check')) {
            if (isset($_FILES) && count($_FILES)) {
                $mime_types = mimetypes();
                foreach ($_FILES as $each) {
                    // Here can be param,

                    if (isset($each['name']) && !empty($each['name']) && !empty($each['tmp_name'])) {
                        if (is_array($each['name'])) {
                            for ($i = 0; $i < count($each['name']); $i++) {
                                $file_info = pathinfo($each['name'][$i]);
                                if (!empty($file_info['extension'])) {
                                    $extension = strtolower($file_info['extension']);
                                    if (isset($mime_types[$extension])) {
                                        if (is_array($mime_types[$extension])) {
                                            $valid_mimetype = FALSE;
                                            foreach ($mime_types[$extension] as $each_mimetype) {
                                                if ($each_mimetype == $each['type'][$i]) {
                                                    $valid_mimetype = TRUE;
                                                    break;
                                                }
                                            }
                                            if (!$valid_mimetype) {
                                                die('Prevented an unwanted file upload attempt - 1! Unknown MIME Type '.$each['type'][$i]);
                                            }
                                            unset($valid_mimetype);
                                        } else {
                                            if ($mime_types[$extension] !== $each['type'][$i]) {
                                                die('Prevented an unwanted file upload attempt - 2! Unknown MIME Type '.$each['type'][$i]);
                                            }
                                        }
                                    }
                                    unset($file_info, $extension);
                                }
                            }
                        } else {
                            $file_info = pathinfo($each['name']);
                            if (!empty($file_info['extension'])) {
                                $extension = strtolower($file_info['extension']);
                                if (isset($mime_types[$extension])) {
                                    if (is_array($mime_types[$extension])) {
                                        $valid_mimetype = FALSE;
                                        foreach ($mime_types[$extension] as $each_mimetype) {
                                            if ($each_mimetype == $each['type']) {
                                                $valid_mimetype = TRUE;
                                                break;
                                            }
                                        }
                                        if (!$valid_mimetype) {
                                            die('Prevented an unwanted file upload attempt - 3! Unknown MIME Type '.$each['type']);
                                        }
                                        unset($valid_mimetype);
                                    } else {
                                        if ($mime_types[$extension] !== $each['type']) {
                                            die('Prevented an unwanted file upload attempt - 4! Unknown MIME Type '.$each['type']);
                                        }
                                    }
                                }
                                unset($file_info, $extension);
                            }
                        }
                    }
                }
                unset($mime_types);
            }
        }
    }

    /**
     * Check for alteration of file extensions to prevent unwanted payload executions
     * https://securelist.com/blog/virus-watch/74297/png-embedded-malicious-payload-hidden-in-a-png-file/
     *
     * @param string $file_src  The tmp src file
     * @param string $file_ext  The current tmp src file extensions
     * @param array  $valid_ext All accepted file extensions
     *
     * @return bool
     */
    public static function mimeCheck($file_src, $file_ext, $valid_ext) {
        if (extension_loaded('fileinfo')) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $type = $finfo->file($file_src);
            $mime_types = mimetypes();

            // build the mime type according to the allowed extension.
            $check_type = [];
            foreach ($valid_ext as $ext) {
                $ext = strtolower(ltrim($ext, '.'));
                if (isset($mime_types[$ext])) {
                    $check_type[$ext][] = $mime_types[$ext];
                }
            }
            $check_ext = strtolower(ltrim($file_ext, '.'));
            if (!empty($check_type[$check_ext])) {
                if (is_array($check_type[$check_ext])) {
                    if (in_array_r($type, $check_type[$check_ext])) {
                        return TRUE;
                    }
                }
            }
            return FALSE;
        }
        /*
         * Abort mimecheck because the webserver does not have this extension.
         */
        return TRUE;
    }
}

require_once(__DIR__.'/../mimetypes_include.php');
