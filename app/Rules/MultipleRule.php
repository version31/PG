<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class MultipleRule implements Rule
{


    public $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    public function passes($attribute, $value)
    {
        return ((int)$value) % $this->number == 0;

    }


    public function message()
    {
        return sprintf("قیمت بر %s بخش پذیر نمی باشد.", $this->number);
    }
}
