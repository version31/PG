<?php


namespace App\Sh4;


trait Sh4HasPagination
{

    public function scopeHasPagination($query, $request , $perDefault = 1)
    {

        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? $perDefault ;
        $p['offset'] = ($p['page'] - 1) * $p['per'];


        if ($p['per'] && $p['page'])
            $query = $query->offset($p['offset'])
                ->limit($p['per']);



        return $query;
    }

}
