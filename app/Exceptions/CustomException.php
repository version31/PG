<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Exception;

class CustomException extends Exception
{

    public $respond;


    public function __construct($respond = [])
    {
        $this->respond = $respond;
    }


    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return new ErrorResource($this->respond);
    }
}
