<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoryRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\SuccessResource;
use App\Storyable;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;

class StoryController extends Controller
{
    public function store(StoryRequest $request)
    {


        $fields = $request->only(["title", "type"]);


        if ($request->hasFile('media_path'))
            $fields['media_path'] = $this->storeMedia($request->file('media_path'), $request->get("type"));


        $fields['status'] = 0;
        $fields['storyable_type'] = User::class;
        $fields['storyable_id'] = Auth::id();
        $storyId = Storyable::insertGetId($fields);


        $new = Storyable::where('id', $storyId)->first();


        return new SuccessResource();
//        return new BasicResource($new);

    }
}
