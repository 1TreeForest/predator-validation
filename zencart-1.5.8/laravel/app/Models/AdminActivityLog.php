<?php
/**
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: $
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class AdminActivityLog extends Eloquent
{
    protected $table = TABLE_ADMIN_ACTIVITY_LOG;
    protected $primaryKey = 'log_id';
    protected  $hidden = ['gzpost'];
    public $timestamps = false;
}
