<?php

namespace App\Http\Controllers\API;

use App\Direct;
use App\User;
use Result;
use Auth;
use App\Http\Controllers\Controller;

class DirectController extends Controller
{
    //
    public function index_()
    {
        $current_userId = Auth::user()->id;
        $directed_userIds = [$current_userId];
        $directs = Direct::select('receiver_id', 'user_id', 'id')
            ->where('user_id', $current_userId)
            ->orWhere('receiver_id', $current_userId)
            ->orderBy('id', 'Desc')
            ->get();

        foreach ($directs->toArray() as $direct) {
            if (!in_array($direct['receiver_id'], $directed_userIds))
                $directed_userIds[] = $direct['receiver_id'];
            if (!in_array($direct['user_id'], $directed_userIds))
                $directed_userIds[] = $direct['user_id'];
        }

        unset($directed_userIds[0]);

        $directed_userIds = array_values($directed_userIds);

        $directed_userIds = array_map('intval', $directed_userIds);

        $all_usersIds = User::select('*')->pluck('id')->toArray();

        $diff = array_diff($all_usersIds, $directed_userIds);

        $pattern_ids = array_merge($directed_userIds, $diff);

        $sorted = User::select('id', 'first_name', 'last_name')->whereIn('id', $directed_userIds)->get()->sortBy(function ($model) use ($pattern_ids) {
            return array_search($model->id, $pattern_ids);
        });

        return $sorted->values()->all();

    }


    public function index()
    {

        $current_userId = Auth::user()->id;

        $directs = [];

        $avatar =  \DB::raw("CONCAT('".env('App_URL')."/uploads/',`avatar`) AS avatar");

        $query1 = Direct::select('receiver_id as audience_id', 'directs.id', 'directs.created_at', \DB::raw("CONCAT(`first_name`,' ',`last_name`) AS name"), $avatar)
            ->where('user_id', $current_userId)
            ->leftJoin('users', 'users.id', '=', 'directs.receiver_id');

        $query2 = Direct::select('user_id as audience_id', 'directs.id', 'directs.created_at', \DB::raw("CONCAT(`first_name`,' ',`last_name`) AS name") , $avatar)
            ->where('receiver_id', $current_userId)
            ->leftJoin('users', 'users.id', '=', 'directs.user_id');

        $query = $query2->union($query1)->orderBy('created_at', 'Desc')->get()->toArray();

        foreach ($query as $item)
            if (!$this->in_array($item['audience_id'], $directs)) {
                unset($item['id']);
                $directs[] = $item;
            }

        $data = ['directs' => $directs];

        return Result::setData($data)->get();

    }

    public function in_array($needle, $array)
    {
        foreach ($array as $item)
            if ($needle == $item['audience_id'])
                return true;
        return false;
    }
}
