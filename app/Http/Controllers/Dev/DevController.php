<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Rules\MediaPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DevController extends Controller
{

    public $columns;

    /**
     * @param Request $request
     * @param $table
     * @param null $type
     * @return mixed
     *
     * regex_resource
     * from: \"(.*)\",
     * to:   "$1" => \$this->$1,
     */


    public function getTableColumns(Request $request, $table, $type = null)
    {
        $this->columns = DB::getSchemaBuilder()->getColumnListing($table);


        switch ($type) {
            case "fillable":
                return $this->getFillable();
                break;
            default:
                return $this->columns;
        }

        // OR

        return Schema::getColumnListing($table);
    }

    /**
     * @param Request $request
     * @return array
     *
     * regex
     * from:    ,
     * to:      => 'required|',
     */
    public function getKeys(Request $request)
    {
        return $request->keys();
    }


    public function rules()
    {
        $method = $this->method();

        switch ($method) {
            case 'POST':
            {
                return [
                    'name' => 'required | string',
                    'gender' => 'required | in:NOT-SELECTED,MALE,FEMALE',

                    "role" => 'required|in:CANDIDATE,EMPLOYER',
                    "username" => [
                        'required',
                        'string',
                        'max:255',
                        'unique:users',
                        'alpha_dash'
                    ],
                    'password' => [
                        'required',
                        'min:6',
                        'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
                        'confirmed'
                    ],
                    "email" => [
                        'required',
                        'email',
                        'unique:users'
                    ],
                    "tag" => [
                        'required',
                        'string',
                    ],
                    "phone" => [
                        'required',
                        'regex:/[0-9]{10}/'
                    ],
                ];
            }
            case 'PUT':
            {
                return [
                    'name' => 'required | string',
//                        'gender' => 'required | in:NOT-SELECTED,MALE,FEMALE',
                    'avatar_path' => 'string', new MediaPath()
                ];
            }


            default:
                break;
        }
    }


    public function uploadVideo($originalMedia)
    {
        $extension = $originalMedia->getClientOriginalExtension();
        $name = '_' . time() . '.' . $extension;
        $path = public_path();
        $originalMedia->move($path, $name);

        return $this->pathMedia . $name;
    }

    public function store(Request $request)
    {
        return $request->all();
    }

    private function getFillable()
    {
        $columns = $this->columns;
        $diff = array_diff($columns, ["id", "created_at", "updated_at"]);
        $values = array_values($diff);
        echo 'protected $fillable = ';
        $this->print_array($values);
    }

    private function print_array($array, $end = true)
    {
        echo '[';
        echo "\n";
        foreach ($array as $key => $value) {
            echo '"';
            echo $value;
            echo '",';
            echo "\n";
        }
        echo ']';

        if ($end)
            echo ";";
    }

}
