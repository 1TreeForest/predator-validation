<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: form_datepicker.php
| Author: Frederick MC Chan (Chan)
| Credits:  eternicode @ http://bootstrap-datepicker.readthedocs.org/en/latest/
| Docs: http://bootstrap-datepicker.readthedocs.org/en/release/options.html
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

/**
 * Input to save date using datepicker
 * Datetimepicker documentation - https://getdatepicker.com/4/Options/
 *
 * Callback Usages
 * ==========================
 * Configuration when type is 'timestamp'
 * Token used for $options['date_format_php'] is the <a href="http://php.net/manual/en/function.date.php">PHP token equivalent.</a>
 * Token used for $options['date_format_js'] must be formatted with <a href="http://momentjs.com/docs/#/displaying/">moment.js.</a>
 * Both token must match each other to parse the callback properly.
 * Example 1:
 * "date_format_php" => "d-m-Y",
 * "date_format_js"  => "DD-MM-YYYY",
 *
 * Example 2:
 * 'date_format_js'  => 'YYYY-M-DD',
 * 'date_format_php' => 'Y-m-d',
 *
 * Joining 2 datepickers (Start and End)
 * =======================================
 * In Start Datepicker, add $options['join_to_id'] with End Datepicker's input_id
 * In End Datepicker, add $options['join_from_id'] with Start Datepicker's input_id
 *
 * @param string $input_name
 * @param string $label
 * @param string $input_value
 * @param array  $options
 *
 * @return string
 */
function form_datepicker($input_name, $label = '', $input_value = '', array $options = []) {

    $locale = fusion_get_locale();

    $input_value = clean_input_value($input_value);

    $title = $label ? stripinput($label) : ucfirst(strtolower(str_replace("_", " ", $input_name)));

    $input_name = stripinput($input_name);

    $default_options = [
        'input_id'               => $input_name, // The value of attribute id of input.
        'required'               => FALSE,
        'placeholder'            => '',
        'deactivate'             => FALSE, // You can pass true and turn off the javascript datepicker plugin
        'width'                  => '', // A valid value for CSS width
        'inner_width'            => '', // in px i.e. 250px
        'class'                  => '', // The value of attribute class of the input.
        'inline'                 => FALSE, // True if the input should be an inline element
        'error_text'             => $locale['error_input_default'], // An error message
        'date_format_js'         => 'M-DD-YYYY H:mm:ss', // Date format for datepicker plugin.
        'date_format_php'        => 'm-d-Y H:i:s', // Date format for datepicker plugin.
        'delimiter'              => '-',
        'fieldicon_off'          => FALSE, // If TRUE, the calendar icon will be not displayed in the input.
        'filtered_dates'         => [], // must be an array
        'include_filtered_dates' => FALSE, // if true, then only days filtered are selectable
        'weekend'                => [], // 0 for Sunday, 1 for Monday, 6 for Saturday
        'disable_weekend'        => FALSE, // if true, all weekend will be non-selectable
        'type'                   => 'timestamp', // timestamp|date, The date will be saved as mysql date or timestamp. A timestamp will be saved as an integer.
        'tip'                    => '',
        'showTime'               => FALSE,
        'week_start'             => fusion_get_settings('week_start'), // An integer between 0 and 6. It is the same as the attribute weekStart of datepicker.
        'join_to_id'             => '',
        'join_from_id'           => '',
        'debug'                  => '',
        'stacked'                => '',
    ];

    $options += $default_options;

    if (!defined('DATEPICKER')) {
        define('DATEPICKER', TRUE);
        if (file_exists(LOCALE.LOCALESET."includes/dynamics/assets/datepicker/locale/tooltip/".$locale['datepicker'].".js")) {
            $lang = $locale['datepicker'];
        } else {
            $lang = 'en-gb';
        }
        add_to_footer("<script src='".LOCALE.LOCALESET."includes/dynamics/assets/datepicker/locale/tooltip/".$lang.".js'></script>");

        if (defined('BOOTSTRAP4')) {
            add_to_head("<link href='".DYNAMICS."assets/datepicker/bs4/tempusdominus-bootstrap-4.min.css' rel='stylesheet'>");
        } else {
            add_to_head("<link href='".DYNAMICS."assets/datepicker/bs3/bootstrap-datetimepicker.min.css' rel='stylesheet'>");
        }

        add_to_footer("<script src='".DYNAMICS."assets/datepicker/moment.min.js'></script>");

        if (defined('BOOTSTRAP4')) {
            add_to_footer("<script src='".DYNAMICS."assets/datepicker/bs4/tempusdominus-bootstrap-4.min.js'></script>");
        } else {
            add_to_footer("<script src='".DYNAMICS."assets/datepicker/bs3/bootstrap-datetimepicker.min.js'></script>");
        }
        add_to_footer("<script src='".LOCALE.LOCALESET."includes/dynamics/assets/datepicker/locale/".$locale['datepicker'].".js'></script>");
    }

    if (!empty($input_value)) {
        if ($options['type'] == "timestamp") {
            $input_value = date($options['date_format_php'], isnum($input_value) ? $input_value : strtotime(str_replace('-', '/', $input_value)));
        } else if ($options['type'] == "date") {
            if (stristr($input_value, $options['delimiter'])) {
                $input_value = explode($options['delimiter'], $input_value);
                if (count($input_value) == 3) {
                    $params = [
                        'year'  => $input_value[0],
                        'month' => $input_value[1],
                        'day'   => $input_value[2]
                    ];
                    if (checkdate($params['month'], $params['day'], $params['year'])) {
                        $input_value = (implode("-", $params)." 00:00:00");
                    }
                }
            }
        }
    } else {
        $input_value = "";
    }

    if (!$options['width']) {
        $options['width'] = $default_options['width'];
    }

    // Format disabled or enabled dates as JS array
    $dateFilter = [];
    if (!empty($options['filtered_dates']) && is_array($options['filtered_dates'])) {
        $date_filtered = [];
        $dateFilter[0] = "disabledDates: ";
        if ($options['include_filtered_dates'] == TRUE) {
            $dateFilter[0] = "enabledDates: ";
        }
        foreach ($options['filtered_dates'] as $value) {
            $date_filtered[] = date("m/d/Y", $value);
        }
        $dateFilter[1] = "['".implode("','", $date_filtered)."']";
    }

    // Format for Weekend
    $weekendFilter = [];
    if ($options['disable_weekend']) {
        $weekendFilter[0] = "daysOfWeekDisabled: ";
        $weekendFilter[1] = (!empty($options['weekend']) && is_array($options['weekend'])) ? "[".implode(",", $options['weekend'])."]" : "[0,6]";
    }

    if (!in_array($options['type'], ['date', 'timestamp'])) {
        $options['type'] = $default_options['type'];
    }

    $options['week_start'] = (int)$options['week_start'];

    $error_class = "";
    if (\Defender::inputHasError($input_name)) {
        $error_class = "has-error ";
        if (!empty($options['error_text'])) {
            $new_error_text = \Defender::getErrorText($input_name);
            if (!empty($new_error_text)) {
                $options['error_text'] = $new_error_text;
            }
            addnotice("danger", $options['error_text']);
        }
    }


    $html = "<div id='".$options["input_id"]."-field' class='form-group ".($options['inline'] && $label ? 'row ' : '').$error_class.$options['class']."'>\n";

    $html .= ($label ? "<label class='control-label".($options['inline'] ? " col-xs-12 col-sm-3 col-md-3 col-lg-3" : '')."' for='".$options["input_id"]."'>".$label.($options['required'] ? "<span class='required'>&nbsp;*</span> " : '').($options['tip'] ? "<i class='pointer fa fa-question-circle' title='".$options['tip']."'></i>" : '')."</label>" : "");

    $html .= $options['inline'] && $label ? "<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>\n" : "";

    $html .= "<div id='".$options["input_id"]."_datepicker' data-target-input='nearest' class='input-group date'".($options['width'] ? " style='width: ".$options['width']."'" : "").">\n";

    $html .= "<input type='text' name='".$input_name."' id='".$options["input_id"]."' data-target='#".$options["input_id"]."-datepicker' value='".$input_value."' class='datetimepicker-input form-control textbox'".($options['inner_width'] ? " style='width:".$options['inner_width'].";'" : '').($options['placeholder'] ? " placeholder='".$options['placeholder']."'" : '')."/>\n";

    $html .= "<span class='input-group-addon input-group-append ".($options['fieldicon_off'] ? 'display-none' : '')."' data-target='#".$options["input_id"]."-datepicker' data-toggle='datetimepicker'><i class='input-group-text fa fa-calendar'></i></span>\n";

    $html .= "</div>\n";

    $html .= (($options['required'] == 1 && \Defender::inputHasError($input_name)) || \Defender::inputHasError($input_name) ? "<div id='".$options["input_id"]."-help' class='label label-danger p-5 display-inline-block'>".$options['error_text']."</div>" : "");

    $html .= $options['stacked'];

    $html .= ($options['inline'] && $label ? "</div>" : "");

    $html .= "</div>";

    \Defender::add_field_session([
        'input_name'  => clean_input_name($input_name),
        'type'        => $options['type'],
        'title'       => $title,
        'id'          => $options["input_id"],
        'required'    => $options['required'],
        'error_text'  => $options['error_text'],
        "delimiter"   => $options['delimiter'],
        'date_format' => $options['date_format_php'],
        'safemode'    => TRUE,
    ]);

    if (!$options['deactivate']) {

        /**
         * Bind to and from together
         */
        $bindingJs = "";
        if (!empty($options['join_from_id'])) {
            if (defined('BOOTSTRAP4')) {
                $bindingJs = "
                    $('#".$options['join_from_id']."_datepicker').on('change.datetimepicker', function (e) {
                        $('#".$options["input_id"]."_datepicker').datetimepicker('minDate', e.date);
                    });
                    $('#".$options["input_id"]."_datepicker').on('change.datetimepicker', function (e) {
                        $('#".$options['join_from_id']."_datepicker').datetimepicker('maxDate', e.date);
                    });
                ";
            } else {
                $bindingJs = "
                    $('#".$options['join_from_id']."_datepicker').on('dp.change', function(e) {
                        $('#".$options["input_id"]."_datepicker').data('DateTimePicker').minDate(e.date);
                    });
                    $('#".$options["input_id"]."_datepicker').on('dp.change', function(e) {
                        $('#".$options['join_from_id']."_datepicker').data('DateTimePicker').maxDate(e.date);
                    });
                ";
            }
        }

        if (defined('BOOTSTRAP4')) {
            $dpbuttons = "
                buttons: {
                    showToday: true,
                    showClear: true,
                    showClose: true
                },
            ";
        } else {
            $dpbuttons = "
                showTodayButton: true,
                showClear: true,
                showClose: true,
            ";
        }

        add_to_jquery("
        moment.updateLocale('".$locale['datepicker']."', {
            week: {dow: ".$options['week_start']."}
        });

        let ".$options["input_id"]."_datepicker = $('#".$options["input_id"]."_datepicker').datetimepicker({
            locale: '".$locale['datepicker']."',
            ".$dpbuttons."
            allowInputToggle: true,
            icons: {
                time: 'fa fa-clock',
                date: 'fa fa-calendar',
                up: 'fa fa-caret-up',
                down: 'fa fa-caret-down',
                previous: 'fa fa-caret-left',
                next: 'fa fa-caret-right',
                today: 'fa fa-calendar-day',
                clear: 'fa fa-trash',
                close: 'fa fa-close'
            },
            tooltips: tooltips_locale,
            ".($options['showTime'] == TRUE ? "sideBySide: true," : "")."
            ".(!empty($dateFilter) ? $dateFilter[0].$dateFilter[1]."," : "")."
            ".(!empty($weekendFilter) ? $weekendFilter[0].$weekendFilter[1]."," : "")."
            format: '".$options['date_format_js']."',
            ".(!empty($options['join_from_id']) ? "useCurrent: false" : "")."
        });
        ".$bindingJs."
        ");
    }

    return $html;
}
