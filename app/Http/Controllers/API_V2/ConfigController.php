<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use Illuminate\Http\Request;

use Auth;

class ConfigController extends Controller
{
    public function index()
    {

        $data['last_version'] = $this->lastVersionAPI;
        $data['minimum_version'] = $this->minimumVersionAPI;
        $data['current_version'] = $this->getCurrentVersion();
        $data['current_user'] = Auth::user() ? Auth::user()->only(['id', 'name', 'mobile', 'status', 'shop_expired_at', 'limit_insert_product', 'role']) : null;
        $data['ip'] = \Request::ip();

        return new BasicResource($data);
    }


    private function getCurrentVersion()
    {
        $segment = request()->segment(2);

        $versionRegex = '/^v(?<zipCode>\d{1})$/';
        preg_match($versionRegex, $segment, $matches);

        return $matches['zipCode'];

    }
}
