<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Hekmatinasser\Verta\Verta;

class TestController extends Controller
{
    //

    #todo test
    public function changeRole($userId, $roleId)
    {
        $roles = [1, 2, 3];


        if ($userId != 2 OR !($roleId == 1 OR $roleId == 2 OR $roleId == 3))
            return 'You are not allowed to do this';

        $roleId = $roleId - 1;


        if (User::where('id', 2)->update(['role_id' => $roles[$roleId]]))
            return 'role id changed to ' . $roles[$roleId];


    }


    public function index()
    {


        return  Verta::now();
    }
}
