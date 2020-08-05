<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationRequest;
use App\Http\Resources\SuccessResource;
use App\invitation;
use Illuminate\Http\Request;
use Auth;

class InvitationController extends Controller
{
    //

    public function store(InvitationRequest $request)
    {
        $fields['mobile'] = $request->get('mobile');
        $fields['user_id'] = Auth::user()->id;

        if (invitation::create($fields))
            return new SuccessResource();
    }
}
