<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Dec 24 Modified in v1.5.8-alpha $
 */
require('includes/application_top.php');

if (isset($_GET['tID'])) $selected_template = (int)$_GET['tID'];
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$template_info = zen_get_catalog_template_directories();

if (!empty($action)) {
  switch ($action) {
    case 'insert':
      $selected_template = zen_register_new_template($_POST['ln'], $_POST['lang']);
      $action = '';
      break;

    case 'save':
      zen_update_template_name_for_id($selected_template, $_POST['ln']);
      zen_redirect(zen_href_link(FILENAME_TEMPLATE_SELECT, zen_get_all_get_params(['action'])));
      break;

    case 'deleteconfirm':
        zen_deregister_template_id($_POST['tID']);
        zen_redirect(zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page']));
        $action = '';
      break;
  }
}
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
  <head>
      <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
  </head>
  <body>
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
    <!-- body //-->
    <div class="container-fluid">
      <h1><?php echo HEADING_TITLE; ?></h1>
      <div class="row"><?php echo TEXT_TEMPLATE_SELECT_INFO;?></div>
      <div class="row">
        <!-- body_text //-->
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 configurationColumnLeft">
          <table class="table table-hover">
            <thead>
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE; ?></th>
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></th>
                <th class="dataTableHeadingContent text-center"><?php echo TABLE_HEADING_DIRECTORY; ?></th>
                <th class="dataTableHeadingContent text-right"><?php echo TABLE_HEADING_ACTION; ?></th>
              </tr>
            </thead>
            <tbody>
                <?php
                $template_query_raw = "SELECT * FROM " . TABLE_TEMPLATE_SELECT;
                $template_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $template_query_raw, $template_query_numrows);
                $templates = $db->Execute($template_query_raw);
                foreach ($templates as $template) {
                  if (!isset($template_info[$template['template_dir']])) {
                     $template_info[$template['template_dir']] = [
                       'name' => '<strong class="errorText"> MISSING DIRECTORY: ' . $template['template_dir'] . '</strong>',
                       'version' => '',
                       'author' => '',
                       'description' => '',
                       'screenshot' => '',
                       'missing' => true,
                     ];
                  }
                  if ((!isset($selected_template) || $selected_template == $template['template_id']) && !isset($tInfo) && $action != 'new') {
                    $tInfo = new objectInfo($template);
                  }

                    if (isset($tInfo) && is_object($tInfo) && ($template['template_id'] == $tInfo->template_id)) {
                        if ($action === 'edit') { ?>
                            <tr id="defaultSelected" class="dataTableRowSelected">
                        <?php } else { ?>
                            <tr id="defaultSelected" class="dataTableRowSelected" style="cursor:pointer" onclick="document.location.href='<?php echo zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&tID=' . $tInfo->template_id . '&action=edit'); ?>'">
                        <?php }
                    } else { ?>
                        <tr class="dataTableRow" style="cursor:pointer" onclick="document.location.href='<?php echo zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&tID=' . $template['template_id']); ?>'">
                    <?php }
                  if ($template['template_language'] == '0') {
                    $template_language = TEXT_INFO_DEFAULT_LANGUAGE;
                  } else {
                    $template_language = zen_get_language_name($template['template_language']);
                  }
                  ?>
              <td class="dataTableContent"><?php echo $template_language; ?></td>
              <td class="dataTableContent"><?php echo $template_info[$template['template_dir']]['name']; ?></td>
              <td class="dataTableContent text-center"><?php echo $template['template_dir']; ?></td>
              <td class="dataTableContent text-right">
                  <?php
                  if (isset($tInfo) && is_object($tInfo) && ($template['template_id'] == $tInfo->template_id)) {
                    echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                  } else {
                    echo '<a href="' . zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&tID=' . $template['template_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                  }
                  ?>
                &nbsp;</td>
              </tr>
              <?php
            }
            ?>
            </tbody>
          </table>
            <div class="row">
                <div class="col-xs-6"><?php echo $template_split->display_count($template_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TEMPLATES); ?></div>
                <div class="col-xs-6 text-right"><?php echo $template_split->display_links($template_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 configurationColumnRight">
            <?php
            if (isset($tInfo) && is_object($tInfo)) {
                if ($tInfo->template_language == '0') {
                    $template_language = TEXT_INFO_DEFAULT_LANGUAGE;
                } else {
                    $template_language = zen_get_language_name($tInfo->template_language);
                }
            }
            $heading = [];
            $contents = [];

            switch ($action) {
              case 'new':
                $heading[] = ['text' => '<h4>' . TEXT_INFO_HEADING_NEW_TEMPLATE . '</h4>'];

                $contents = ['form' => zen_draw_form('zones', FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&action=insert', 'post', 'class="form-horizontal"')];
                $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
                foreach($template_info as $key => $value) {
                  if (isset($value['missing'])) continue;
                  $template_array[] = [
                    'id' => $key,
                    'text' => $value['name'],
                      ];
                }
                $lns = zen_get_template_languages_not_registered();
                foreach ($lns as $ln) {
                  $language_array[] = [
                      'id' => $ln['language_id'],
                      'text' => $ln['language_name'],
                      ];
                }
                $contents[] = ['text' => zen_draw_label(TEXT_INFO_TEMPLATE_NAME, 'ln', 'class="control-label"') . zen_draw_pull_down_menu('ln', $template_array, '', 'class="form-control" id="ln"')];
                $contents[] = ['text' => zen_draw_label(TEXT_INFO_LANGUAGE_NAME, 'lang', 'class="control-label"') . zen_draw_pull_down_menu('lang', $language_array, '', 'class="form-control" id="lang"')];
                $contents[] = ['align' => 'text-center', 'text' => '<button type="submit" class="btn btn-primary">' . IMAGE_INSERT . '</button> <a href="' . zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page']) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'];
                break;

              case 'edit':
                $heading[] = ['text' => '<h4>' . TABLE_HEADING_LANGUAGE . ': '  . $template_language . '</h4>'];

                $contents = ['form' => zen_draw_form('templateselect', FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&tID=' . $tInfo->template_id . '&action=save', 'post', 'class="form-horizontal"')];
                $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
                foreach($template_info as $key => $value) {
                  if (isset($value['missing'])) continue;
                  $template_array[] = ['id' => $key, 'text' => $value['name']];
                }
                $contents[] = ['text' => zen_draw_label(TEXT_INFO_TEMPLATE_NAME, 'ln', 'class="control-label"') . zen_draw_pull_down_menu('ln', $template_array, $templates->fields['template_dir'], 'class="form-control" id="ln"')];
                $contents[] = ['align' => 'text-center', 'text' => '<button type="submit" class="btn btn-primary">' . IMAGE_UPDATE . '</button> <a href="' . zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&tID=' . $tInfo->template_id) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'];
                break;

              case 'delete':
                $heading[] = ['text' => '<h4>' . TABLE_HEADING_LANGUAGE . ': '  . $template_language . '</h4>'];

                $contents = ['form' => zen_draw_form('zones', FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&action=deleteconfirm') . zen_draw_hidden_field('tID', $tInfo->template_id)];
                $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
                $contents[] = ['text' => '<b>' . $template_info[$tInfo->template_dir]['name'] . '</b>'];
                $contents[] = ['align' => 'text-center', 'text' => '<button type="submit" class="btn btn-danger">' . IMAGE_DELETE . '</button> <a href="' . zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&tID=' . $tInfo->template_id) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'];
                break;

              default:
                if (isset($tInfo) && is_object($tInfo)) {
                 $heading[] = ['text' => '<h4>' . TABLE_HEADING_LANGUAGE . ': '  . $template_language . '</h4>'];

                    if ($tInfo->template_language == '0') {
                        $contents[] = ['text' => '<h5>' . TEXT_INFO_DEFAULT_TEMPLATE . '</h5>'];
                    }
                 $contents[] = ['text' => TEXT_INFO_TEMPLATE_NAME . ': <strong>"' . $template_info[$tInfo->template_dir]['name'] . '</strong>"'];
                 $contents[] = ['text' => TEXT_INFO_TEMPLATE_AUTHOR . $template_info[$tInfo->template_dir]['author']];
                 $contents[] = ['text' => TEXT_INFO_TEMPLATE_VERSION . $template_info[$tInfo->template_dir]['version']];
                 $contents[] = ['text' => TEXT_INFO_TEMPLATE_DESCRIPTION . '<br>' . $template_info[$tInfo->template_dir]['description']];
                 $contents[] = ['align' => 'text-center',
                    'text' => '<a href="' . zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&tID=' . $tInfo->template_id . '&action=edit') . '" class="btn btn-primary" role="button">' . TEXT_INFO_EDIT_INTRO . '</a>' .
                        ($tInfo->template_language != '0' ? ' <a href="' . zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&tID=' . $tInfo->template_id . '&action=delete') . '" class="btn btn-warning" role="button">' . IMAGE_DELETE . '</a>' : '')];
                  $contents[] = ['text' => '<hr>'];
                  $contents[] = ['text' => TEXT_INFO_TEMPLATE_INSTALLED];
                  foreach($template_info as $key => $value) {
                    $contents[] = array('text' => '<a href="' . DIR_WS_CATALOG_TEMPLATE . $key . '/images/' . $value['screenshot'] . '" rel="noreferrer noopener" target = "_blank" class="btn btn-info" role="button">' . IMAGE_PREVIEW . '</a>&nbsp;&nbsp;' . $value['name']);
                  }
                }
                break;
            }

            if (!empty($heading) && !empty($contents)) {
              $box = new box();
              echo $box->infoBox($heading, $contents);
            }
            ?>
        </div>
          <?php
          if (empty($action)) {
              $template_languages = [];
              foreach ($templates as $template) {
                  $template_languages[] = $template['template_language'];
              }
              foreach ($languages as $language) {
                  if (!in_array($language['id'], $template_languages)) {
              ?>
                      <div class="row text-right">
                          <a href="<?php echo zen_href_link(FILENAME_TEMPLATE_SELECT, 'page=' . $_GET['page'] . '&action=new'); ?>" class="btn btn-primary" role="button"><?php echo IMAGE_NEW_TEMPLATE; ?></a>
                      </div>
              <?php
                    break;
                  }
              }
          }
          ?>
      </div>
      <!-- body_text_eof //-->
    </div>
    <!-- body_eof //-->
    <!-- footer //-->
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    <!-- footer_eof //-->
  </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
