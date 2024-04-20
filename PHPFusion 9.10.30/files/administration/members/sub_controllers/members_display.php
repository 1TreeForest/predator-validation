<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: members_display.php
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

namespace Administration\Members\Sub_Controllers;

use Administration\Members\Members_Admin;
use Administration\Members\Members_View;
use PHPFusion\Quantum\QuantumHelper;
use PHPFusion\QuantumFields;
use PHPFusion\Template;

/**
 * Class Members_Display
 *
 * @package Administration\Members\Sub_Controllers
 */
class Members_Display extends Members_Admin {

    /**
     * List members
     *
     * @return string
     */
    public static function render_listing() {

        fusion_load_script(ADMIN."members/js/user_display.js");

        $c_name = 'usertbl_results';
        $default_selected = ['user_joined', 'user_lastvisit', 'user_groups'];
        $default_status_selected = ['0'];
        $s_name = 'usertbl_status';
        $st_name = 'usertbl_search';
        $selected_status = [];
        $statuses = [];
        $search_text = "";

        if (check_post("apply_filter")) {
            // Display Cookie
            if ($display = post(["display"])) {
                $selected_display_keys = \Defender::sanitize_array(array_keys($display));
                $cookie_selected = implode(',', $selected_display_keys);
            } else {
                // Prevent cookie tampering and reverted to default result
                $cookie_selected = implode(',', $default_selected);
            }
            setcookie($c_name, $cookie_selected, time() + (86400 * 30), "/");
            if ($statuses = post(["user_status"])) {
                $selected_display_keys = \Defender::sanitize_array(array_keys($statuses));
                $status_cookie_selected = implode(',', $selected_display_keys);
            } else {
                // Prevent cookie tampering and reverted to default result
                $status_cookie_selected = implode(',', $default_status_selected);
            }
            setcookie($s_name, $status_cookie_selected, time() + (86400 * 30), "/");
        } else {
            // Callback
            if (!cookie($c_name)) {
                $cookie_selected = implode(',', $default_selected);
                setcookie($c_name, $cookie_selected, time() + (86400 * 30), "/");
            } else {
                $cookie_selected = stripinput(cookie($c_name));
            }

            $status = get("status", FILTER_VALIDATE_INT);
            if (!empty($status) && $status <= 7) {
                $status_cookie_selected = $status;
                setcookie($s_name, $status_cookie_selected, time() + (86400 * 30), "/");
            } else {
                if (!cookie($s_name)) {
                    $status_cookie_selected = implode(',', $default_status_selected);
                    setcookie($s_name, $status_cookie_selected, time() + (86400 * 30), "/");
                } else {
                    $status_cookie_selected = stripinput(cookie($s_name));
                }
            }
        }

        /*
         * Sanitize Cookie Input - Select
         */
        $usertable_column = array_flip(fieldgenerator(DB_USERS));
        unset($usertable_column['user_password']);
        unset($usertable_column['user_admin_password']);
        unset($usertable_column['user_salt']);
        unset($usertable_column['user_algo']);
        unset($usertable_column['user_admin_algo']);
        unset($usertable_column['user_admin_salt']);
        unset($usertable_column['user_status']);

        $user_fields = array_map('trim', explode(',', $cookie_selected));
        // Sanitize fields
        $selected_fields = [];
        if (!empty($user_fields)) {
            foreach ($user_fields as $field_name) {
                if (isset($usertable_column[$field_name])) {
                    // there we have a verified one.
                    $selected_fields[$field_name] = $field_name;
                }
            }
        }
        /*
         * Sanitize Cookie Input - Condition
         */
        $user_status = array_map('trim', explode(',', $status_cookie_selected));
        if (!empty($user_status)) {
            foreach ($user_status as $status) {
                if (isnum($status)) {
                    $selected_status[$status] = $status;
                }
            }
        }

        $tLocale = [
            'user_hide_email' => self::$locale['ME_420'],
            'user_joined'     => self::$locale['ME_421'],
            'user_lastvisit'  => self::$locale['ME_422'],
            'user_ip'         => self::$locale['ME_423'],
            'user_ip_type'    => self::$locale['ME_424'],
            'user_groups'     => self::$locale['ME_425'],
            'user_status'     => self::$locale['ME_427']
        ];

        $field_checkboxes = [
            'user_hide_email' => form_checkbox('display[user_hide_email]', $tLocale['user_hide_email'], (isset($selected_fields['user_hide_email']) ? 1 : 0), ['reverse_label' => TRUE]),
            'user_joined'     => form_checkbox('display[user_joined]', $tLocale['user_joined'], (isset($selected_fields['user_joined']) ? 1 : 0), ['reverse_label' => TRUE]),
            'user_lastvisit'  => form_checkbox('display[user_lastvisit]', $tLocale['user_lastvisit'], (isset($selected_fields['user_lastvisit']) ? 1 : 0), ['reverse_label' => TRUE]),
            'user_ip'         => form_checkbox('display[user_ip]', $tLocale['user_ip'], (isset($selected_fields['user_ip']) ? 1 : 0), ['reverse_label' => TRUE]),
            'user_ip_type'    => form_checkbox('display[user_ip_type]', $tLocale['user_ip_type'], (isset($selected_fields['user_ip_type']) ? 1 : 0), ['reverse_label' => TRUE]),
            'user_groups'     => form_checkbox('display[user_groups]', $tLocale['user_groups'], (isset($selected_fields['user_groups']) ? 1 : 0), ['reverse_label' => TRUE]),
        ];

        $extra_checkboxes = [];
        $result = dbquery("SELECT field_id, field_name, field_title FROM ".DB_USER_FIELDS." ORDER BY field_cat, field_order ASC");
        if (dbrows($result) > 0) {
            $data = dbarray($result);
            $name = $data['field_name'];
            $title = (QuantumHelper::isSerialized($data['field_title']) ? QuantumFields::parseLabel($data['field_title']) : $data['field_title']);
            $tLocale[$name] = $title;
            $extra_checkboxes[$name] = form_checkbox("display[".$name."]", $title, (isset($selected_fields[$name]) ? 1 : 0), ['input_id' => 'custom_'.$data['field_id'], 'reverse_label' => TRUE]);
        }

        $field_status = [];
        for ($i = 0; $i < 9; $i++) {
            if ($i < 8 || self::$settings['enable_deactivation'] == 1) {
                $field_status[$i] = form_checkbox('user_status['.$i.']', getsuspension($i), (isset($selected_status[$i]) ? 1 : 0), ['input_id' => 'user_status_'.$i, 'reverse_label' => TRUE]);
            }
        }

        $search_bind = [];
        $search_cond = '';
        $field_to_search = array_merge(array_values(['user_name', 'user_id', 'user_email']), array_keys($extra_checkboxes));

        if (check_post("search_text")) {
            $search_text = sanitizer('search_text', '', 'search_text');
            setcookie($st_name, $search_text, time() + (86400 * 30), "/");
        } else {
            if ($search_text_cookie = cookie($st_name)) {
                $search_text = stripinput($search_text_cookie);
            }
        }

        if (!empty($search_text)) {
            $search_cond = 'AND (';
            $i = 0;
            foreach (array_values($field_to_search) as $key) {
                $search_cond .= "$key LIKE :text_$i".($i == count($field_to_search) - 1 ? '' : ' OR ');
                $search_bind[':text_'.$i] = '%'.$search_text.'%';
                $i++;
            }
            $search_cond .= ')';
        }

        if (!empty($selected_status)) {
            $status_cond = " WHERE user_status IN (".implode(',', $selected_status).") ";
            $status_bind = [];
            foreach ($selected_status as $susp_i) {
                $statuses[$susp_i] = $susp_i;//'<strong>'.getsuspension($susp_i).'</strong>';
            }
        } else {
            $status_cond = ' WHERE user_status=:status';
            $status_bind = [
                ':status' => 0,
            ];
            $statuses = [0 => 0];
        }

        $query_bind = array_merge($status_bind, $search_bind);
        $rowCount = dbcount('(user_id)', DB_USERS, ltrim($status_cond, 'WHERE ').$search_cond, $query_bind);
        $rowstart = check_get('rowstart') && get('rowstart', FILTER_SANITIZE_NUMBER_INT) <= $rowCount ? get('rowstart') : 0;
        $limit = 16;
        $newrows = 0;
        $newrowsCount = 0;
        if (in_array(2, $selected_status)) {
            $newrowsCount = dbcount('(user_name)', DB_NEW_USERS);
            $nquery = "SELECT * FROM ".DB_NEW_USERS;
            $nresult = dbquery($nquery);
            $i = 999999;
            while ($data = dbarray($nresult)) {
                $newrows++;
                $list[$data['user_name']] = [
                    'user_id'      => $i,
                    'checkbox'     => '',
                    'user_name'    => "<div class='clearfix'>\n<div class='pull-left m-r-10'>".display_avatar($data, '35px', '', FALSE)."</div>
                        <div class='overflow-hide'>".$data['user_name']."<br/>".getsuspension(2)."</div></div>",
                    'user_status'  => getsuspension(2),
                    'user_level'   => self::$locale['ME_562'],
                    'user_actions' => "<a href='".self::$status_uri['activate'].$data['user_name']."&amp;code=".$data['user_code']."'>".self::$locale['ME_507']."</a> - <a href='".self::$status_uri['delete'].$data['user_name']."&amp;newuser=1'>".self::$locale['delete']."</a>",
                    'user_email'   => $data['user_email']."<br /><a href='".self::$status_uri['resend'].$data['user_name']."' title='".self::$locale['u165']."'><i class='fa fa-envelope fa-lg m-r-10'></i></a>",
                    'user_joined'  => showdate('longdate', $data['user_datestamp']),
                ];
                $i++;
            }
        }

        $query = "SELECT user_id, user_name, user_avatar, user_email, user_level, user_status".($cookie_selected ? ', '.$cookie_selected : '')."
                  FROM ".DB_USERS.$status_cond.$search_cond." LIMIT $rowstart, $limit";

        $result = dbquery($query, $query_bind);

        $rows = dbrows($result);

        $page_nav = ($rowCount > $limit ? makepagenav($rowstart, $limit, $rowCount, 5, FUSION_SELF.fusion_get_aidlink().'&amp;') : '');

        $interface = new static();

        $list_sum = sprintf(self::$locale['ME_407'], implode(', ', array_map([$interface, 'list_uri'], $statuses)), $rows + $newrows, $rowCount + $newrowsCount);

        if ($rows != '0') {
            while ($data = dbarray($result)) {
                // the key which to be excluded should be unset
                $key = array_keys($data);
                foreach ($key as $data_key) {
                    switch ($data_key) {
                        case 'user_joined':
                        case 'user_lastvisit':
                            $data[$data_key] = !empty($data[$data_key]) ? showdate('shortdate', $data[$data_key]) : '-';
                            break;
                        case 'user_groups':
                            if (!empty($data[$data_key])) {
                                $group = array_filter(explode('.', $data[$data_key]));
                                $groups = "<ul class='block'>";
                                foreach ($group as $group_id) {
                                    $groups .= '<li><a href="'.BASEDIR.'profile.php?group_id='.$group_id.'">'.getgroupname($group_id).'</a></li>';
                                }
                                $groups .= "</ul>\n";
                                $data[$data_key] = $groups;
                            }
                            break;
                        case 'user_hide_email':
                            $data[$data_key] = $data[$data_key] ? self::$locale['ME_415'] : self::$locale['ME_416'];
                            break;
                    }
                    // custom ones
                    $list[$data['user_id']][$data_key] = $data[$data_key];
                }

                $list[$data['user_id']]['checkbox'] = ($data['user_level'] > USER_LEVEL_SUPER_ADMIN) ? form_checkbox('user_id[]', '', '', ['input_id' => 'user_id_'.$data['user_id'], 'value' => $data['user_id']]) : '';

                $list[$data['user_id']]['user_name'] = "<div class='clearfix'>\n<div class='pull-left m-r-10'>".display_avatar($data, '35px', '', FALSE)."</div>\n
                <div class='overflow-hide'><a href='".self::$status_uri['view'].$data['user_id']."'>".$data['user_name']."</a><br/>".getsuspension($data['user_status'])."</div>
                </div>\n";

                $login_as_link = "";
                if ($data['user_status'] != 2 && fusion_get_userdata("user_level") <= $data["user_level"] && fusion_get_userdata("user_id") != $data["user_id"]) {
                    $login_as_link = " - <a href='".self::$status_uri['login_as'].$data['user_id']."'>".self::$locale["ME_508"]."</a>";
                }

                if ($data['user_status'] == 2 && fusion_get_userdata("user_id") != $data["user_id"]) {
                    $login_as_link = " - <a href='".self::$status_uri['reactivate'].$data['user_id']."'>".self::$locale["ME_507"]."</a>";
                }

                $list[$data['user_id']]['user_actions'] = ($data['user_level'] > USER_LEVEL_SUPER_ADMIN ? "<a href='".self::$status_uri['edit'].$data['user_id']."'>".self::$locale['edit']."</a> - <a href='".self::$status_uri['delete'].$data['user_id']."'>".self::$locale['delete']."</a> -" : "")." <a href='".self::$status_uri['view'].$data['user_id']."'>".self::$locale['view']."</a>".$login_as_link;

                $list[$data['user_id']]['user_level'] = getuserlevel($data['user_level']);

                $list[$data['user_id']]['user_email'] = $data['user_email'];

                $list[$data['user_id']]['user_status'] = getuserstatus($data['user_status']);
            }
        }

        // Render table header and table result
        $detail_span = count($selected_fields) + 1;
        $table_head = "<tr><th class='min'></th><th colspan='4' class='text-center'>".self::$locale['ME_408']."</th><th colspan='$detail_span' class='text-center'>".self::$locale['ME_409']."</th></tr>";

        $table_subheader = "<th></th><th>".self::$locale['ME_410']."</th><th>".self::$locale['actions']."</th><th class='min'>".self::$locale['ME_411']."</th>\n<th>".self::$locale['ME_427']."</th>\n<th class='min'>".self::$locale['ME_412']."</th>";

        foreach ($selected_fields as $column) {
            if (!empty($tLocale[$column])) {
                $table_subheader .= "<th>".$tLocale[$column]."</th>\n";
            }
        }

        $table_subheader = "<tr>$table_subheader</tr>\n";
        $table_footer = "<tr><th colspan='".($detail_span + 5)."'>".form_checkbox('check_all', self::$locale['ME_414'], '', ['class' => 'm-b-0', 'reverse_label' => TRUE])."</th></tr>\n";

        $list_result = "<tr>\n<td colspan='".(count($selected_fields) + 5)."' class='text-center'>".self::$locale['ME_405']."</td>\n</tr>\n";
        if (!empty($list)) {
            $list_result = '';
            foreach ($list as $user_id => $prop) {
                $list_result .= call_user_func_array([$interface, 'list_func'], [$user_id, $list, $selected_fields]);
            }
        }

        /*
         * User Actions Button
         */
        $user_actions = form_button('action', self::$locale['ME_501'], self::USER_REINSTATE, ['class' => 'btn-success']).
            form_button('action', self::$locale['ME_500'], self::USER_BAN, ['input_id' => 'action_ban']).
            form_button('action', self::$locale['ME_502'], self::USER_DEACTIVATE, ['input_id' => 'action_deactivate']).
            form_button('action', self::$locale['ME_503'], self::USER_SUSPEND, ['input_id' => 'action_suspend']).
            form_button('action', self::$locale['ME_504'], self::USER_SECURITY_BAN, ['input_id' => 'action_security_ban']).
            form_button('action', self::$locale['ME_505'], self::USER_CANCEL, ['input_id' => 'action_cancel']).
            form_button('action', self::$locale['ME_506'], self::USER_ANON, ['input_id' => 'action_anon']);

        $html = openform('member_frm', 'post', FUSION_SELF.fusion_get_aidlink(), ['class' => 'form-inline']);
        $html .= form_hidden('aid', '', iAUTH);

        $tpl = Template::getInstance('member_listing');

        $tpl->set_locale(self::$locale);

        //$tpl->set_tag("user_table_id", fusion_table("user_table"));

        $tpl->set_tag('filter_text', form_text('search_text', '', $search_text, [
            'placeholder'        => self::$locale['ME_401'],
            'append'             => TRUE,
            'append_button'      => TRUE,
            'append_value'       => self::$locale['search'],
            'append_form_value'  => 'search_member',
            'append_button_name' => 'search_member',
            'class'              => 'm-b-0 m-r-10 display-inline-block',
            'group_size'         => 'sm'
        ]));
        $tpl->set_tag('filter_button', '<button class="btn btn-default btn-sm" type="button" data-toggle="collapse" data-target="#filterpanel" aria-expanded="false" aria-controls="filterpanel">'.self::$locale['ME_402'].' <span class="caret"></span></button>');
        $tpl->set_tag('action_button', "<a class='btn btn-success btn-sm m-l-5' href='".FUSION_SELF.fusion_get_aidlink()."&amp;ref=add'>".self::$locale['ME_403']."</a>");
        $tpl->set_tag('filter_status', "<span class='m-r-15 display-inline-block'>".implode("</span><span class='m-r-15 display-inline-block'>", array_values($field_status))."</span>");
        $tpl->set_tag('filter_options', "<span class='m-r-15 display-inline-block'>".implode("</span><span class='m-r-15 display-inline-block'>", array_values($field_checkboxes))."</span>");
        $tpl->set_tag('filter_extras', "<span class='m-r-15 display-inline-block'>".implode("</span><span class='m-r-15 display-inline-block'>", array_values($extra_checkboxes))."</span>");
        $tpl->set_tag('filter_apply_button', form_button('apply_filter', self::$locale['ME_404'], 'apply_filter', ['class' => 'btn-primary']));
        $tpl->set_tag('page_count', $list_sum);
        $tpl->set_tag('list_head', $table_head);
        $tpl->set_tag('list_column', $table_subheader);
        $tpl->set_tag('list_result', $list_result);
        $tpl->set_tag('list_footer', $table_footer);
        $tpl->set_tag('page_nav', $page_nav);
        $tpl->set_tag('user_actions', $user_actions);
        $tpl->set_text(Members_View::display_members());

        $html .= $tpl->get_output();

        $html .= closeform();

        return $html;
    }

    /**
     * List member link
     *
     * @param $value
     *
     * @return string
     */
    protected function list_uri($value) {
        return !empty(self::$status_uri[$value]) ? "<a href='".self::$status_uri[$value]."'><strong>".getsuspension($value)."</strong></a>\n" : '';
    }

    /**
     * Listing formatter for user results
     *
     * @param $user_id
     * @param $list
     * @param $selected_fields
     *
     * @return string
     */
    private static function list_func($user_id, $list, $selected_fields) {
        $html = "<tr id='user-".$user_id."'>\n
                <td class='p-10'>\n".$list[$user_id]['checkbox']."</td>\n
                <td>".$list[$user_id]['user_name']."</td>\n
                <td class='no-break'>".$list[$user_id]['user_actions']."</td>\n
                <td class='no-break'>\n".$list[$user_id]['user_level']."</td>\n
                <td class='no-break'>\n".$list[$user_id]['user_status']."</td>\n
                <td>\n".$list[$user_id]['user_email']."</td>\n";

        add_to_jquery('$("#user_id_'.$user_id.'").click(function() {
        if ($(this).prop("checked")) {
            $("#user-'.$user_id.'").addClass("active");
        } else {
            $("#user-'.$user_id.'").removeClass("active");
        }
        });');

        foreach ($selected_fields as $column) {
            $html .= "<td>".(!empty($list[$user_id][$column]) ? $list[$user_id][$column] : "-")."</td>\n";
        }

        $html .= "</tr>\n";

        return $html;
    }

}
