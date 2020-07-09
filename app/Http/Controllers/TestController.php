<?php

namespace App\Http\Controllers;

use App\Helpers\EmailAdapter;
use App\Models\Program;
use App\Product;
use Carbon\Carbon;
use Ipecompany\Smsirlaravel\Smsirlaravel;
use function foo\func;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Auth;

class TestController extends Controller
{


    public function index()
    {
        return Smsirlaravel::send(['test1','test2'],['09185257989','09128182951']);
    }
    public function setUserId()
    {
        DB::table('emails')->update(['user_id' => Auth::user()->id]);
    }


    public function updateEmails()
    {
        $items = Program::orderBy('id', 'Desc')->limit(10)->get();
        foreach ($items as $item) {
            $this->updateEmail($item->id);
        }

        $this->setUserId();

    }

    public function updateEmail($id)
    {
        $email = new EmailAdapter();
        $email->set($id)->adapt()->create();
    }
}
