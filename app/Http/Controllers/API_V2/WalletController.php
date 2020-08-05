<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoneyRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\WalletTransactionsCollection;
use App\MoneyRequests;
use App\User;
use Bavix\Wallet\Models\Transaction;
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


    public function transactions()
    {
        $query = Transaction::select([
            'transactions.created_at','transactions.id','transactions.type','transactions.amount','transactions.meta',
//            'users.mobile','users.first_name','users.last_name',
            'users.id as user__id',
            'transfers.to_id','transfers.from_id'
        ])
            ->leftJoin('users','transactions.payable_id','=','users.id')
            ->leftJoin('transfers','transactions.payable_id','=','users.id')
            ->where('payable_id',1)
            ->groupBy('transactions.id')
            ->orderBy('transactions.id','Desc')

            ->get()
        ;

        return WalletTransactionsCollection::collection($query);
    }


}
