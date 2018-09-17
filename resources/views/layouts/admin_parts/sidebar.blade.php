@section('sidebar')

    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu  page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <li class="nav-item start ">
                    <a href="javascript:;" class="" style="cursor: default;">
                        <span class="title">{{ 'Welcome, ' .  Auth::user()->first_name  }}</span>
                    </a>
                </li>
                <li class="heading">
                    <h3 class="uppercase">Work</h3>
                </li>
                <li class="nav-item {{ $data['section'] == 'products-new' ? ' active open' : '' }}">
                    <a href="{{ route('admin.products.new') }}" class="nav-link nav-toggle">
                        <i class="fa fa-plus-square"></i>
                        <span class="title"> New Posts </span>
                        <?php $count = \Illuminate\Support\Facades\Auth::user()->newPostsCount(); ?>
                        @if($count > 0)
                            <span id="new-posts-count" class="badge badge-danger">{{ $count }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item {{ $data['section'] == 'users' ? ' active open' : '' }}">
                    <a href="{{ route('users.index') }}" class="nav-link nav-toggle">
                        <i class="fa fa-user"></i>
                        <span class="title"> Users </span>
                    </a>
                </li>
                <li class="nav-item {{ $data['section'] == 'categories' ? ' active open' : '' }}">
                    <a href="{{ route('categories.index') }}" class="nav-link nav-toggle">
                        <i class="fa fa-list"></i>
                        <span class="title"> Categories </span>
                    </a>
                </li>
                <li class="nav-item {{ $data['section'] == 'subcategories' ? ' active open' : '' }}">
                    <a href="{{ route('subcategories.index') }}" class="nav-link nav-toggle">
                        <i class="fa fa-list-alt"></i>
                        <span class="title"> Subcategories </span>
                    </a>
                </li>
                <li class="nav-item {{ $data['section'] == 'products' ? ' active open' : '' }}">
                    <a href="{{ route('admin.products.all') }}" class="nav-link nav-toggle">
                        <i class="fa fa-archive"></i>
                        <span class="title"> Products </span>
                    </a>
                </li>
                <li class="nav-item {{ $data['section'] == 'products-bulk' ? ' active open' : '' }}">
                    <a href="{{ route('admin.products.bulk', ['admin']) }}" class="nav-link nav-toggle">
                        <i class="fa fa-upload"></i>
                        <span class="title"> Bulk Upload </span>
                    </a>
                </li>
                <li class="nav-item ">
                    @if(\Illuminate\Support\Facades\App::environment('local'))
                    <a href="{{ route('home') }}" class="nav-link nav-toggle">
                    @else
                    <a href="http://www.rentinghood.com" class="nav-link nav-toggle">
                    @endif
                        <i class="fa fa-desktop"></i>
                        <span class="title"> Public Website </span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ route('logout') }}" class="nav-link nav-toggle">
                        <i class="fa fa-sign-out"></i>
                        <span class="title"> Logout </span>
                    </a>
                </li>
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->

@endsection