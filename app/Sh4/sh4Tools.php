<?php

namespace App\Sh4;




trait sh4Tools
{
    public function jsonToArray($json)
    {
        $array = json_decode($json , true);

        $keys = array_keys($array);

        return $keys;

    }

}
