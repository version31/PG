<ul class="sidebar-menu" data-widget="tree">
    <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->

    <li class="treeview">
        <a>
            <i class="fa fa-user"></i> <span>حساب کاربری</span>
            <span class="pull-left-container">
                  <i class="fa fa-angle-right pull-left"></i>
                </span>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="/admin/users/admin/edit">
                    <i class="fa fa-pencil"></i>
                    ویرایش ادمین
                </a>
            </li>
            <li>
                <a href="/admin/users/current/password">
                    <i class="fa fa-key"></i>
                    <span>تغییر پسورد</span>
                </a>
            </li>
        </ul>
    </li>

    @foreach($items as $item)


        @if( isset($item->permission) ? Gate::check($item->permission) : true)
            <li>
                <a href="{{$item->link}}">
                    <i class="fa {{$item->icon}}"></i>
                    <span>{{$item->title}}</span></a>
            </li>
        @endif


    @endforeach
</ul>
