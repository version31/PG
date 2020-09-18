<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Plan;
use App\Sh4\sh4Path;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Intervention\Image\Facades\Image;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, sh4Path;

    public $lastVersionAPI = 1;
    public $minimumVersionAPI = 1;

    public $pathThumbnail = '/uploads/thumbnail/';
    public $pathMedia = '/uploads/media/';
    public $maxAllowedVideos = 6;
    public $cropWidthThumbnail = 300;
    public $cropHeightThumbnail = 300;
    public $maxAllowedWidth = 700;

    public function __construct()
    {
//        Auth::onceUsingId(274); #todo sh4: for testing




    }


    public function test()
    {
        $order = new Order();
        $order->set(new OrderItem(Plan::class, 1));
        $order->set(new OrderItem(Plan::class, 2));
        $order->set(new OrderItem(Plan::class, 3));
        return $order->items();
    }

    public function getCurrentersion()
    {
        $segment = request()->segment(2);

        $versionRegex = '/^v(?<zipCode>\d{1})$/';
        preg_match($versionRegex, $segment, $matches);

        return $matches['zipCode'];

    }


    public function storePicture($originalMedia)
    {
        $originalPath = public_path() . $this->pathMedia;
        $thumbnailPath = public_path() . $this->pathThumbnail;
        $extension = $originalMedia->getClientOriginalExtension();
        $name = $this->quickRandom() . '_' . time() . '.' . $extension;

        $img = Image::make(($originalMedia->getRealPath()));

        $img->resize($this->maxAllowedWidth, null, function ($constraint) {
            $constraint->aspectRatio();
        });

//        $img->save($originalPath . $name, 90);
        $img->save($originalPath . $name);

        if ($img->height() > $img->width())
            $img->resize($this->cropWidthThumbnail, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        else
            $img->resize(null, $this->cropWidthThumbnail, function ($constraint) {
                $constraint->aspectRatio();
            });


        $img->crop($this->cropWidthThumbnail, $this->cropHeightThumbnail);

//        $img->save($thumbnailPath . $name, 90);
        $img->save($thumbnailPath . $name);

        return $this->pathMedia . $name;
    }

    public function storeVideo($originalMedia)
    {
        $extension = $originalMedia->getClientOriginalExtension();
        $name = $this->quickRandom() . '_' . time() . '.' . $extension;
        $path = public_path() . $this->pathMedia;
        $originalMedia->move($path, $name);

        return $this->pathMedia . $name;
    }

    public function storeMedia($media, $type)
    {
        if ($type == 'video')
            return $this->storeVideo($media);
        elseif ($type == 'picture')
            return $this->storePicture($media);

    }


    public function quickRandom($length = 5)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }


    public function getTypeMedia($originalMedia)
    {

        if (is_array($originalMedia))
            $originalMedia = $originalMedia[0];

        $defaultType = false;

        $type = $defaultType;

        $videoTypes = ['avi', 'mp4', 'mpeg', 'quicktime'];
        $pictureTypes = ['jpeg', 'png', 'jpg', 'gif', 'svg'];


        if ($originalMedia)
            $extension = $originalMedia->getClientOriginalExtension();
        else   return $type;

        if (in_array($extension, $videoTypes))
            $type = 'video';
        elseif (in_array($extension, $pictureTypes))
            $type = 'picture';


        return $type;
    }

    public function adaptDate($date)
    {
        if ($date) {
            return $shop_expired_at = date('Y-m-d H:i:s', substr($date, 0, -3));
        }
    }

}
