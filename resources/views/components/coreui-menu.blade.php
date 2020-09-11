<ul class="c-sidebar-nav ps ps--active-y">
    @foreach($items as $item)
        @if( isset($item->permission) ? Gate::check($item->permission) : true)

            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link c-active" href="{{$item->link}}">
                    <i class="fa {{$item->icon}}"></i>
                    <span>{{$item->title}}</span>
                </a>
            </li>
        @endif
    @endforeach
</ul>
