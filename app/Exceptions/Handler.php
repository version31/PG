<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if ($exception instanceof BalanceIsEmpty) {
            return new ErrorResource(["wallet" => "بالانس کیف پول صفر است"]);
        }

        elseif ($exception instanceof InsufficientFunds) {
            return new ErrorResource(["wallet" => "بالانس کیف پول کمتر از مقدار درخواستی جهت پرداخت است"]);
        }
        return parent::render($request, $exception);
    }
}
