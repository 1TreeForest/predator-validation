<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Scott C Wilson 2022 Jan 09 New in v1.5.8-alpha $
*/

$define = [
    'HEADING_TITLE' => 'EZ-Pages',
    'TABLE_HEADING_PAGES' => 'Page Title',
    'TABLE_HEADING_VSORT_ORDER' => 'Sidebox Sort Order',
    'TABLE_HEADING_HSORT_ORDER' => 'Footer Sort Order',
    'TEXT_PAGES_TITLE' => 'Page Title:',
    'TEXT_PAGES_HTML_TEXT' => 'HTML Content:',
    'TEXT_PAGES_STATUS_CHANGE' => 'Status Change: %s',
    'TEXT_INFO_DELETE_INTRO' => 'Are you sure you want to delete this page?',
    'SUCCESS_PAGE_INSERTED' => 'Success: The page has been inserted.',
    'SUCCESS_PAGE_UPDATED' => 'Success: The page has been updated.',
    'SUCCESS_PAGE_REMOVED' => 'Success: The page has been removed.',
    'SUCCESS_PAGE_STATUS_UPDATED' => 'Success: The status of the page has been updated.',
    'ERROR_PAGE_TITLE_REQUIRED' => 'Error: Page title required.',
    'ERROR_UNKNOWN_STATUS_FLAG' => 'Error: Unknown status flag.',
    'ERROR_MULTIPLE_HTML_URL' => 'Error: you have defined multiple settings when only one may be defined per Link ...<br>Only define either: HTML Content -or- Internal Link URL -or- External Link URL',
    'TABLE_HEADING_STATUS_HEADER' => 'Header:',
    'TABLE_HEADING_STATUS_SIDEBOX' => 'Sidebox:',
    'TABLE_HEADING_STATUS_FOOTER' => 'Footer:',
    'TABLE_HEADING_STATUS_TOC' => 'TOC:',
    'TABLE_HEADING_CHAPTER' => 'Chapter:',
    'TABLE_HEADING_VISIBLE' => 'Visible:',
    'TABLE_HEADING_PAGE_OPEN_NEW_WINDOW' => 'Open New Window:',
    'TABLE_HEADING_PAGE_IS_SSL' => 'Page is SSL:',
    'TABLE_HEADING_PAGE_IS_VISIBLE' => 'Page is Visible:',
    'TABLE_HEADING_PAGE_IS_VISIBLE_EXPLANATION' => ' Page shown even if not in header, footer or sidebox<br>
(If all the settings for Visible and Header and Footer and Sidebox are all OFF then visitors attempting to see the page will get a Page-Not-Found response.)',
    'TEXT_DISPLAY_NUMBER_OF_PAGES' => 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> pages)',
    'IMAGE_NEW_PAGE' => 'New Page',
    'TEXT_INFO_PAGES_ID' => 'ID: ',
    'TEXT_INFO_PAGES_ID_SELECT' => 'Select a page ...',
    'TEXT_HEADER_SORT_ORDER' => 'Order:',
    'TEXT_SIDEBOX_SORT_ORDER' => 'Order:',
    'TEXT_FOOTER_SORT_ORDER' => 'Order:',
    'TEXT_TOC_SORT_ORDER' => 'Order:',
    'TEXT_CHAPTER' => 'Prev/Next Chapter:',
    'TABLE_HEADING_CHAPTER_PREV_NEXT' => 'Chapter:&nbsp;<br>',
    'TEXT_HEADER_SORT_ORDER_EXPLAIN' => 'Header Sort Order used while generating pages in single row for the header; Sort order should be greater than zero to enable this page in the row type listing',
    'TEXT_SIDEBOX_ORDER_EXPLAIN' => 'Sidebox Sort order is used when pages are listed in vertical links; Sort order should be greater than zero to enable it in vertical listing, else it will be considered as HTML text for special purposes',
    'TEXT_FOOTER_ORDER_EXPLAIN' => 'Footer Sort Order used while generating pages in single row footer; Sort order should be greater than zero to enable this page in the row type listing',
    'TEXT_TOC_SORT_ORDER_EXPLAIN' => 'TOC (Table of Contents) Sort Order used while generating pages that are customized as either a single row (header/footer, etc) or vertically, based on individual needs; Sort order should be greater than zero to enable this page in the listing',
    'TEXT_CHAPTER_EXPLAIN' => 'Chapters are used with TOC (Table of Contents) Sort Order for the display on Previous/Next. Links in the TOC will consist of pages matching this chapter number, and will be displayed in the TOC Sort Order',
    'TEXT_ALT_URL' => 'Internal Link URL:',
    'TEXT_ALT_URL_EXPLAIN' => 'If specified, the page content will be ignored and this INTERNAL alternate URL will be used to make the link<br>Example to Reviews: index.php?main_page=reviews<br>Example to My Account: index.php?main_page=account and mark as SSL',
    'TEXT_ALT_URL_EXTERNAL' => 'External Link URL:',
    'TEXT_ALT_URL_EXTERNAL_EXPLAIN' => 'If specified, the page content will be ignored and this EXTERNAL alternate URL will be used to make the link<br>Example to external link: http://www.sashbox.net',
    'TEXT_SORT_CHAPTER_TOC_TITLE_INFO' => 'Display Order: ',
    'TEXT_SORT_CHAPTER_TOC_TITLE' => 'Chapter/TOC',
    'TEXT_SORT_HEADER_TITLE' => 'Header',
    'TEXT_SORT_SIDEBOX_TITLE' => 'Sidebox',
    'TEXT_SORT_FOOTER_TITLE' => 'Footer',
    'TEXT_SORT_PAGE_TITLE' => 'Page Title',
    'TEXT_SORT_PAGE_ID_TITLE' => 'Page ID, Title',
    'TEXT_PAGE_TITLE' => 'Title:',
    'TEXT_WARNING_MULTIPLE_SETTINGS' => '<strong>WARNING: Multiple Link Definition</strong>',
];

return $define;
