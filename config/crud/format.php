<?php

use Hekmatinasser\Verta\Facades\Verta;

return [
    'shamsi' => function ($row) {
        return Verta::instance($row->created_at);
    },
    'number' => function ($row) {
        return number_format($row->price);
    },
    'star' => function ($row) {
        $value = config('crud.status.plans.type')[$row->type];
        $value = $row->extra ? $value . "($row->extra)" : $value;
        return $value;
    },
    'todo' => function ($row) {
        return 'TODO';
    },
    'userLink' => function ($row) {

        $title = $row->user->name ?? $row->user->mobile;
        $href = '/admin/users/' . $row->user->id;
        return view('crud::partials.link', compact('title', 'href'));
    },
];
