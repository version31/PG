<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoneyRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\SuccessResource;
use App\MoneyRequests;
use App\User;
use Illuminate\Http\Request;
use Auth;

class WalletController extends Controller
{
    //
    public function transfer(TransferRequest $request)
    {

        $current = Auth::user();
        $user = User::where('mobile', $request->get('mobile'))->first();

        $current->transfer($user, $request->get('price'));

        return new SuccessResource("انتقال با موفقیت انجام گرفت");
    }

    public function moneyRequest(MoneyRequest $request)
    {

        $fields = $request->all();
        $fields['user_id'] = Auth::user()->id;

        $row = MoneyRequests::create($fields);

        if ($row)
            return new SuccessResource();
    }


}
