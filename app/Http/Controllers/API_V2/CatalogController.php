<?php

namespace App\Http\Controllers\API_V2;

use App\Catalog;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Plan;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    //

    public $path = '/uploads/catalogs/';

    public function store(CatalogRequest $request)
    {
        $fields = $request->only(['title', 'description']);
        $fields['user_id'] = \Auth::id();


        //upload
        $fields['path'] = $this->uploadFile($request->file('file'));

        $row = Catalog::create($fields);


        if ($row)
            return new SuccessResource("رکورد با موفقیت اضافه شد");
        else
            return new ErrorResource("در انجام عملیات خطایی رخ داده است");

    }


    public function destroy($id)
    {

        $row = Catalog::find($id);

        if ($row)
            \File::delete(public_path() . $row->path);

        $destroyed = Catalog::destroy($id);

        if ($destroyed)
            return new SuccessResource("رکورد با موفقیت حذف شد");
        else
            return new ErrorResource("در انجام عملیات خطایی رخ داده است");
    }


    public function update(CatalogRequest $request, $id)
    {
        $row = Catalog::find($id);

        $fields = $request->only(['title', 'description']);

        if ($row && $request->file('file')) {
            \File::delete(public_path() . $row->path);
            $fields['path'] = $this->uploadFile($request->file('file'));
        }

        return $request->all();

        $new = $row->update($fields);

        if ($new)
            return new SuccessResource("رکورد با موفقیت ویرایش شد");
        else
            return new ErrorResource("در انجام عملیات خطایی رخ داده است");

    }


    public function uploadFile($file)
    {
        $filename = time() . '.' . $file->extension();
        $filePath = public_path() . $this->path;
        $file->move($filePath, $filename);
        return $this->path . $filename;
    }
}
