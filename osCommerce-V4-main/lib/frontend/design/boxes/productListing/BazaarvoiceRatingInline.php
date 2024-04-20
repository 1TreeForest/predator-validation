<?php
/**
 * This file is part of osCommerce ecommerce platform.
 * osCommerce the ecommerce
 *
 * @link https://www.oscommerce.com
 * @copyright Copyright (c) 2000-2022 osCommerce LTD
 *
 * Released under the GNU General Public License
 * For the full copyright and license information, please view the LICENSE.TXT file that was distributed with this source code.
 */

namespace frontend\design\boxes\productListing;

class BazaarvoiceRatingInline extends \yii\base\Widget
{
    public $file;
    public $params;
    public $settings;

    public function run()
    {
        return '<div>Bazaarvoice Rating Inline</div>';
    }
}