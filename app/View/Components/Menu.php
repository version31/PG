<?php

namespace App\View\Components;

use App\Helpers\Assoc;
use Illuminate\View\Component;

class Menu extends Component
{
    public $items;
    public $view;


    public function __construct($group = 'main', $view)
    {

        $this->items = \App\Menu::where('group', $group)->get();

        $this->view = $view;
    }


    public function render()
    {
        return view('components.' . $this->view);
    }


}


