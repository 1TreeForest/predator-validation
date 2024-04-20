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

use common\classes\Migration;

/**
 * Class m220828_083820_add_supplier_sort
 */
class m220828_083820_add_supplier_sort extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumnIfMissing('suppliers_products', 'sort_order', $this->integer());
        $this->addColumnIfMissing('products', 'auto_price_modified', $this->dateTime());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m220828_083820_add_supplier_sort cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220828_083820_add_supplier_sort cannot be reverted.\n";

        return false;
    }
    */
}
