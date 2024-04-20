<?php
/**
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: $
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class AdminMenu extends Eloquent
{
    protected $table = TABLE_ADMIN_MENUS;
    protected $primaryKey = 'menu_key';
    public $timestamps = false;
    protected $keyType = 'string';
}
