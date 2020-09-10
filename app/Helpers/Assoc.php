<?php


namespace App\Helpers;


class Assoc
{
    public static function setObject($assoc)
    {
        $obj= [];
        foreach ($assoc as $arr) {
            $obj[] = new Obj($arr);
        }
        return $obj;
    }
}

class Obj
{
    public function __construct($item)
    {
        foreach ($item as $property => $value) {
            $this->{$property} = $value;
        }
    }
}
