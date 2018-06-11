@section('sidebar')

    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <ul class="page-sidebar-menu  page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <li class="nav-item start ">
                    <a href="javascript:;" class="" style="cursor: default;">
                        <span class="title">{{ 'Welcome, ' .  Auth::user()->first_name  }}</span>
                    </a>
                </li>
                <li class="heading">
                    <h3 class="uppercase">Work</h3>
                </li>
                <li class="nav-item {{ $data['section'] == 'users' ? ' active open' : '' }}">
                    <a href="{{ route('users.index') }}" class="nav-link nav-toggle">
                        <i class="fa fa-user"></i>
                        <span class="title"> Users </span>
                        <span class="arrow"></span>
                    </a>
                </li>

                <li class="nav-item {{ $data['section'] == 'categories' ? ' active open' : '' }}">
                    <a href="{{ route('categories.index') }}" class="nav-link nav-toggle">
                        <i class="fa fa-list"></i>
                        <span class="title"> Categories </span>
                        <span class="arrow"></span>
                    </a>
                </li>

                <li class="nav-item {{ $data['section'] == 'products' ? ' active open' : '' }}">
                    <a href="{{ route('users.index') }}" class="nav-link nav-toggle">
                        <i class="fa fa-archive"></i>
                        <span class="title"> Products </span>
                        <span class="arrow"></span>
                    </a>
                </li>
                <li class="nav-item {{ $data['section'] == 'products-new' ? ' active open' : '' }}">
                    <a href="{{ route('products.new') }}" class="nav-link nav-toggle">
                        <i class="fa fa-archive"></i>
                        <span class="title"> New Product Posts </span>
                        <span class="arrow"></span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ route('logout') }}" class="nav-link nav-toggle">
                        <i class="fa fa-sign-out"></i>
                        <span class="title"> Logout </span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                </li>
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->

@endsection