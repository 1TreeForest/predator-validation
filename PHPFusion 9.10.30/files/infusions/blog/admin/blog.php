<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: blog.php
| Author: Frederick MC Chan (Chan)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
$formaction = FUSION_REQUEST;
$locale = fusion_get_locale();
$aidlink = fusion_get_aidlink();
$userdata = fusion_get_userdata();
$blog_settings = get_settings("blog");

// use the select
$blog_cat_opts[0] = $locale['blog_0424'];
$cat_result = dbquery("SELECT blog_cat_id, blog_cat_name FROM ".DB_BLOG_CATS." ".(multilang_table("BL") ? "WHERE ".in_group('blog_cat_language', LANGUAGE) : "")." ORDER BY blog_cat_name ASC");
if (dbrows($cat_result) > 0) {
    while ($bcData = dbarray($cat_result)) {
        $blog_cat_opts[$bcData['blog_cat_id']] = $bcData['blog_cat_name'];
    }
}

$data = [
    'blog_id'             => 0,
    'blog_draft'          => 0,
    'blog_sticky'         => 0,
    'blog_blog'           => '',
    'blog_datestamp'      => time(),
    'blog_extended'       => '',
    'blog_keywords'       => '',
    'blog_breaks'         => 'y',
    'blog_allow_comments' => 1,
    'blog_allow_ratings'  => 1,
    'blog_language'       => LANGUAGE,
    'blog_visibility'     => 0,
    'blog_subject'        => '',
    'blog_start'          => 0,
    'blog_end'            => 0,
    'blog_cat'            => 0,
    'blog_image'          => '',
    'blog_ialign'         => 'pull-left',
];

if (fusion_get_settings('tinymce_enabled') != 1) {
    $data['blog_breaks'] = isset($_POST['blog_breaks']) ? "y" : "n";
} else {
    $data['blog_breaks'] = "n";
}

if (isset($_POST['save']) or isset($_POST['preview'])) {

    $blog_blog = "";
    if ($_POST['blog_blog']) {
        $blog_blog = str_replace("src='".str_replace("../", "", IMAGES_B), "src='".IMAGES_B, $_POST['blog_blog']);
    }

    $blog_extended = "";
    if ($_POST['blog_extended']) {
        $blog_extended = str_replace("src='".str_replace("../", "", IMAGES_B), "src='".IMAGES_B, $_POST['blog_extended']);
    }

    $data = [
        'blog_id'             => form_sanitizer($_POST['blog_id'], 0, 'blog_id'),
        'blog_subject'        => form_sanitizer($_POST['blog_subject'], '', 'blog_subject'),
        'blog_cat'            => isset($_POST['blog_cat']) ? form_sanitizer($_POST['blog_cat'], 0, 'blog_cat') : "",
        'blog_blog'           => form_sanitizer($blog_blog, '', 'blog_blog'),
        'blog_extended'       => form_sanitizer($blog_extended, '', 'blog_extended'),
        'blog_keywords'       => form_sanitizer($_POST['blog_keywords'], '', 'blog_keywords'),
        'blog_ialign'         => form_sanitizer($_POST['blog_ialign'], '', 'blog_ialign'),
        'blog_image'          => "",
        'blog_start'          => form_sanitizer($_POST['blog_start'], 0, 'blog_start'),
        'blog_end'            => form_sanitizer($_POST['blog_end'], 0, 'blog_end'),
        'blog_visibility'     => form_sanitizer($_POST['blog_visibility'], 0, 'blog_visibility'),
        'blog_draft'          => isset($_POST['blog_draft']) ? "1" : "0",
        'blog_sticky'         => isset($_POST['blog_sticky']) ? "1" : "0",
        'blog_breaks'         => isset($_POST['blog_breaks']) ? 'y' : 'n',
        'blog_allow_comments' => isset($_POST['blog_allow_comments']) ? "1" : "0",
        'blog_allow_ratings'  => isset($_POST['blog_allow_ratings']) ? "1" : "0",
        'blog_language'       => form_sanitizer($_POST['blog_language'], LANGUAGE, 'blog_language'),
        'blog_datestamp'      => form_sanitizer($_POST['blog_datestamp'], '', 'blog_datestamp'),
    ];

    if (isset($_POST['preview']) && fusion_safe()) {
        $modal = openmodal('blog_preview', $locale['blog_0141']." - ".$data['blog_subject']);
        $modal .= "<div class='m-b-20'>\n";
        $modal .= "<div class='well'><p><strong>".$locale['blog_0425']."</strong></p>";
        $modal .= parse_text($blog_blog, [
            'parse_smileys'        => FALSE,
            'parse_bbcode'         => FALSE,
            'default_image_folder' => IMAGES_B,
            'add_line_breaks'      => $data['blog_breaks'] == 'y'
        ]);
        $modal .= "</div>";
        $modal .= parse_text($blog_extended, [
            'parse_smileys'        => FALSE,
            'parse_bbcode'         => FALSE,
            'default_image_folder' => IMAGES_B,
            'add_line_breaks'      => $data['blog_breaks'] == 'y'
        ]);
        $modal .= "</div>\n";
        $modal .= closemodal();
        add_to_footer($modal);

    } else {

        if (isset($_FILES['blog_image'])) { // when files is uploaded.
            $upload = form_sanitizer($_FILES['blog_image'], '', 'blog_image');
            if (!empty($upload) && !$upload['error']) {
                $data['blog_image'] = $upload['image_name'];
                $data['blog_image_t1'] = $upload['thumb1_name'];
                $data['blog_image_t2'] = $upload['thumb2_name'];
                $data['blog_ialign'] = (isset($_POST['blog_ialign']) ? form_sanitizer($_POST['blog_ialign'], "pull-left", "blog_ialign") : "pull-left");
            }
        } else { // when files not uploaded but there should be existed check.
            $data['blog_image'] = post('blog_image');
            $data['blog_image_t1'] = post('blog_image_t1');
            $data['blog_image_t2'] = post('blog_image_t2');
            $data['blog_ialign'] = (isset($_POST['blog_ialign']) ? form_sanitizer($_POST['blog_ialign'], "pull-left", "blog_ialign") : "pull-left");
        }

        if ($data['blog_sticky'] == "1") {
            $result = dbquery("UPDATE ".DB_BLOG." SET blog_sticky='0' WHERE blog_sticky='1'");
        } // reset other sticky
        // delete image
        if (isset($_POST['del_image'])) {
            if (!empty($data['blog_image']) && file_exists(IMAGES_B.$data['blog_image'])) {
                unlink(IMAGES_B.$data['blog_image']);
            }
            if (!empty($data['blog_image_t1']) && file_exists(IMAGES_B_T.$data['blog_image_t1'])) {
                unlink(IMAGES_B_T.$data['blog_image_t1']);
            }
            if (!empty($data['blog_image_t2']) && file_exists(IMAGES_B_T.$data['blog_image_t2'])) {
                unlink(IMAGES_B_T.$data['blog_image_t2']);
            }
            $data['blog_image'] = "";
            $data['blog_image_t1'] = "";
            $data['blog_image_t2'] = "";
        }

        if (fusion_safe()) {
            if (dbcount("('blog_id')", DB_BLOG, "blog_id='".$data['blog_id']."'")) {
                dbquery_insert(DB_BLOG, $data, 'update');
                addnotice('success', $locale['blog_0411']);
            } else {
                $data['blog_name'] = $userdata['user_id'];
                dbquery_insert(DB_BLOG, $data, 'save');
                addnotice('success', $locale['blog_0410']);
            }
            redirect(FUSION_SELF.$aidlink);
        }

    }

} else if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_POST['blog_id']) && isnum($_POST['blog_id'])) || (isset($_GET['blog_id']) && isnum($_GET['blog_id']))) {
    $result = dbquery("SELECT * FROM ".DB_BLOG." WHERE blog_id='".(isset($_POST['blog_id']) ? $_POST['blog_id'] : $_GET['blog_id'])."'");
    if (dbrows($result)) {
        $data = dbarray($result);
    } else {
        redirect(FUSION_SELF.$aidlink);
    }
}

echo openform('inputform', 'post', $formaction, ['enctype' => TRUE]);
echo form_hidden("blog_id", "", $data['blog_id']);
echo form_hidden("blog_datestamp", "", $data['blog_datestamp']);

echo '<div class="action-buttons m-b-10">';
echo form_button('save', $locale['blog_0437'], $locale['blog_0437'], ['input_id' => 'save_topbtn', 'class' => 'btn-success btn-sm', 'icon' => 'fa fa-hdd-o']);
echo form_button('preview', $locale['blog_0141'], $locale['blog_0141'], ['input_id' => 'preview-topbtn', 'class' => 'm-l-5 btn-primary btn-sm', 'icon' => 'fa fa-eye']);
echo '</div>';

echo form_text('blog_subject', '', $data['blog_subject'], [
    'required'    => TRUE,
    'placeholder' => $locale['blog_0422'],
    'max_length'  => 200,
    'inner_class' => 'input-lg',
    'error_text'  => $locale['blog_0450']
]);

$snippetSettings = [
    'required'    => TRUE,
    'preview'     => TRUE,
    'html'        => TRUE,
    'autosize'    => TRUE,
    'placeholder' => $locale['blog_0425a'],
    'form_name'   => 'inputform',
    'path'        => IMAGES_B
];
if (fusion_get_settings('tinymce_enabled')) {
    $snippetSettings = ['required' => TRUE, 'type' => 'tinymce', 'tinymce' => 'advanced'];
}
echo form_textarea('blog_blog', $locale['blog_0425'], $data['blog_blog'], $snippetSettings);

$extendedSettings = [];
if (!fusion_get_settings('tinymce_enabled')) {
    $extendedSettings = [
        'preview'     => TRUE,
        'html'        => TRUE,
        'autosize'    => TRUE,
        'placeholder' => $locale['blog_0426b'],
        'form_name'   => 'inputform',
        'path'        => IMAGES_B
    ];
} else {
    $extendedSettings = ['type' => 'tinymce', 'tinymce' => 'advanced', 'path' => IMAGES_B];
}
echo form_textarea('blog_extended', $locale['blog_0426'], $data['blog_extended'], $extendedSettings);
echo "<div class='row'>\n";
echo "<div class='col-xs-12 col-sm-12 col-md-7 col-lg-8'>\n";
openside('');
if ($data['blog_image'] != "" && $data['blog_image_t1'] != "") {
    echo "<div class='row'>\n";
    echo "<div class='col-xs-12 col-sm-6'>\n";
    $image_thumb = get_blog_image_path($data['blog_image'], $data['blog_image_t1'], $data['blog_image_t2']);
    echo "<label>".thumbnail($image_thumb, '100px');
    echo "<input type='checkbox' name='del_image' value='y' /> ".$locale['delete']."</label>\n";
    echo "</div>\n";
    echo "<div class='col-xs-12 col-sm-6'>\n";
    $alignOptions = [
        'pull-left'       => $locale['left'],
        'blog-img-center' => $locale['center'],
        'pull-right'      => $locale['right']
    ];
    echo form_select('blog_ialign', $locale['blog_0442'], $data['blog_ialign'], [
        "options" => $alignOptions,
        "inline"  => FALSE
    ]);
    echo "</div>\n</div>\n";
    echo "<input type='hidden' name='blog_image' value='".$data['blog_image']."' />\n";
    echo "<input type='hidden' name='blog_image_t1' value='".$data['blog_image_t1']."' />\n";
    echo "<input type='hidden' name='blog_image_t2' value='".$data['blog_image_t2']."' />\n";
} else {
    $file_input_options = [
        'upload_path'      => IMAGES_B,
        'max_width'        => $blog_settings['blog_photo_max_w'],
        'max_height'       => $blog_settings['blog_photo_max_h'],
        'max_byte'         => $blog_settings['blog_photo_max_b'],
        // set thumbnail
        'thumbnail'        => 1,
        'thumbnail_w'      => $blog_settings['blog_thumb_w'],
        'thumbnail_h'      => $blog_settings['blog_thumb_h'],
        'thumbnail_folder' => 'thumbs',
        'delete_original'  => 0,
        // set thumbnail 2 settings
        'thumbnail2'       => 1,
        'thumbnail2_w'     => $blog_settings['blog_photo_w'],
        'thumbnail2_h'     => $blog_settings['blog_photo_h'],
        'valid_ext'        => $blog_settings['blog_file_types'],
        'type'             => 'image'
    ];
    echo form_fileinput("blog_image", $locale['blog_0439'], "", $file_input_options);
    echo "<div class='small m-b-10'>".sprintf($locale['blog_0440'], parsebytesize($blog_settings['blog_photo_max_b']))."</div>\n";
    $alignOptions = [
        'pull-left'       => $locale['left'],
        'news-img-center' => $locale['center'],
        'pull-right'      => $locale['right']
    ];
    echo form_select('blog_ialign', $locale['blog_0442'], $data['blog_ialign'], ["options" => $alignOptions]);
}
closeside();

openside('');
echo form_select('blog_keywords', $locale['blog_0443'], $data['blog_keywords'], [
    "max_length"  => 320,
    "placeholder" => $locale['blog_0444'],
    "inner_width" => "100%",
    "width"       => '100%',
    "error_text"  => $locale['blog_0457'],
    "tags"        => TRUE,
    "multiple"    => TRUE
]);

echo "<div class='pull-left m-r-10 display-inline-block'>";
echo form_datepicker('blog_start', $locale['blog_0427'], $data['blog_start'], [
    "placeholder" => $locale['blog_0429'],
    "join_to_id"  => "blog_end",
    "width"       => "250px"
]);
echo "</div><div class='pull-left m-r-10 display-inline-block'>\n";
echo form_datepicker('blog_end', $locale['blog_0428'], $data['blog_end'], [
    "placeholder"  => $locale['blog_0429'],
    "join_from_id" => "blog_start",
    "width"        => "250px"
]);
echo "</div>\n";
closeside();
echo "</div>\n<div class='col-xs-12 col-sm-12 col-md-5 col-lg-4'>\n";

openside('');
echo form_select('blog_cat[]', $locale['blog_0423'], $data['blog_cat'], [
        'options'     => $blog_cat_opts,
        "width"       => "100%",
        'inner_width' => '100%',
        'multiple'    => TRUE,
    ]
);

echo form_select('blog_visibility[]', $locale['blog_0430'], $data['blog_visibility'], [
    'options'     => fusion_get_groups(),
    'placeholder' => $locale['choose'],
    'width'       => '100%',
    'multiple'    => TRUE,
]);

if (multilang_table("BL")) {
    echo form_select('blog_language[]', $locale['global_ML100'], $data['blog_language'], [
        'options'     => fusion_get_enabled_languages(),
        'placeholder' => $locale['choose'],
        'width'       => '100%',
        'multiple'    => TRUE
    ]);
} else {
    echo form_hidden('blog_language', '', $data['blog_language']);
}

echo form_checkbox('blog_draft', $locale['blog_0431'], $data['blog_draft'], [
    'reverse_label' => TRUE,
    'class'         => 'm-b-0'
]);

echo form_checkbox('blog_sticky', $locale['blog_0432'], $data['blog_sticky'], [
    'reverse_label' => TRUE,
    'class'         => 'm-b-0'
]);

if (fusion_get_settings("tinymce_enabled") != 1) {
    echo form_checkbox('blog_breaks', $locale['blog_0433'], $data['blog_breaks'], [
        'value'         => 'y',
        'reverse_label' => TRUE,
        'class'         => 'm-b-0'
    ]);
}

if (!fusion_get_settings("comments_enabled") || !fusion_get_settings("ratings_enabled")) {
    $sys = "";
    if (!fusion_get_settings("comments_enabled") && !fusion_get_settings("ratings_enabled")) {
        $sys = $locale['comments_ratings'];
    } else if (!fusion_get_settings("comments_enabled")) {
        $sys = $locale['comments'];
    } else {
        $sys = $locale['ratings'];
    }

    echo "<div class='well'>".sprintf($locale['blog_0149'], "<strong>$sys</strong>")."</div>\n";
}

echo form_checkbox('blog_allow_comments', $locale['blog_0434'], $data['blog_allow_comments'], [
    'reverse_label' => TRUE,
    'class'         => 'm-b-0'
]);

echo form_checkbox('blog_allow_ratings', $locale['blog_0435'], $data['blog_allow_ratings'], [
    'reverse_label' => TRUE,
    'class'         => 'm-b-0'
]);

closeside();
echo "</div>\n</div>\n";
echo form_button('save', $locale['blog_0437'], $locale['blog_0437'], ['input_id' => 'save_bottombtn', 'class' => 'btn-success', 'icon' => 'fa fa-hdd-o']);
echo form_button('preview', $locale['blog_0141'], $locale['blog_0141'], ['input_id' => 'preview-bottombtn', 'class' => 'm-l-5 btn-primary', 'icon' => 'fa fa-eye']);
echo closeform();
