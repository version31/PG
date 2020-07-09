<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Log;


use App\Storyable;
use Illuminate\Support\Facades\Input;
use Image;
use Result;
use Validator;


class StoryController extends Controller
{
    public function store(Request $request)
    {

        Log::emergency($request->all()); #todo test


        $fields = [
            "title",
        ];


        $validator = Validator::make($request->all(), [
            'media_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000',
            'title' => 'required|string|min:6|max:200',
        ]);


        if ($validator->fails())
            return Result::setErrors([$validator->errors()])->get();


        $columns = $request->only(array_merge($fields, ['status' => -1]));


        if (Input::hasFile('media_path'))
            $columns['media_path'] = $this->storeMedia($request->file('media_path') , 'picture');


        $columns['status'] = 0;
        $columns['storyable_type'] = User::class;
        $columns['storyable_id'] = Auth::user()->id;
        $storyId = Storyable::insertGetId($columns);


        return Result::setData(['story' => Storyable::where('id', $storyId)->first()])->get();
    }


    #todo plz move to controller


}
