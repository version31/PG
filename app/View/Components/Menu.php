<?php

namespace App\View\Components;

use App\Helpers\Assoc;
use Illuminate\View\Component;

class Menu extends Component
{
    public $items;
    public $view;


    public function __construct($items = null, $view)
    {
        $items = [
            [
                "icon" => "fa-tasks",
                "title" => "کاربران",
                "link" => "/admin/users",
                "permission" => "users-read",
            ],
            [
                "icon" => "fa-tasks",
                "title" => "فراهم کنندگان",
                "link" => "/admin/providers",
                "permission" => "providers-read",

            ],
            [
                "icon" => "fa-tasks",
                "title" => "برگه ها",
                "link" => "/admin/pages",
                "permission" => "pages-read",
            ],
            [
                "icon" => "fa-tasks",
                "title" => "مقالات",
                "link" => "/admin/posts",
                "permission" => "posts-read",
            ],
            [
                "icon" => "fa-tasks",
                "title" => "محصولات",
                "link" => "/admin/products",
                "permission" => "products-read",
            ],
            [
                "icon" => "fa-tasks",
                "title" => "مجلات",
                "link" => "/admin/magazines",
                "permission" => "categories-read",
            ],


            [
                "icon" => "fa-tasks",
                "title" => "سرویس ها",
                "link" => "/admin/services",
                "permission" => "services-read",
            ],

            [
                "icon" => "fa-tasks",
                "title" => "استوری ها",
                "link" => "/admin/stories",
                "permission" => "stories-read",
            ],

            [
                "icon" => "fa-tasks",
                "title" => "پلن ها",
                "link" => "/admin/plans",
                "permission" => "plans-read",
            ],


        ];

        $this->items = Assoc::setObject($items);

        $this->view = $view;
    }


    public function render()
    {
        return view('components.' . $this->view);
    }


}


