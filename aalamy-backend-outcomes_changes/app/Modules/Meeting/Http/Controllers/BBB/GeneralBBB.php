<?php
/**
 * Created by PhpStorm.
 * User: Abd Shammout
 * Date: 27/12/2021
 * Time: 11:00 AM
 */

namespace Modules\Meeting\Http\Controllers\BBB;


class GeneralBBB
{

    public static function isConnect(){
        return bigbluebutton()->isConnect();
    }

}
