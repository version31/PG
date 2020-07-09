<?php
/**
 * Created by PhpStorm.
 * User: ali
 * Date: 5/29/19
 * Time: 12:14 PM
 */

namespace App\Helpers;



use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;


class Sh4Helper
{



    public static function adaptTime($value)
    {
        if ($value == '0000-00-00 00:00:00')
            $value = null;

        if ($value) {
            $v = new Verta($value);
            return $v;
        }
    }


    public static function removeTag($string)
    {
//        $string = str_replace('.', '', $string);


        $string = strip_tags($string);
        $string = str_replace(' ', '', $string);
        $string = str_replace("\t", '', $string);
        $string = str_replace("\n", '', $string);
        $string = str_replace("\r", '', $string);

        return $string;
    }


    public static function adaptDate($value , $format = 'Y/n/j')
    {

        if ($value == '0000-00-00 00:00:00' || $value == null )
            return $value = 'نامشخص';

        $v = new Verta($value);
        return $v->format($format);
    }
}
