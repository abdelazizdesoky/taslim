<!-- main-sidebar -->
<div class="app-sidebar__overlay bg-gray-200" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href=""><img src="{{URL::asset('dashboard/img/brand/logo.png')}}" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href=""><img src="{{URL::asset('dashboard/img/brand/logo-white.png')}}" class="main-logo dark-theme" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href=""><img src="{{URL::asset('dashboard/img/brand/favicon.png')}}" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href=""><img src="{{URL::asset('dashboard/img/brand/favicon-white.png')}}" class="logo-icon dark-theme" alt="logo"></a>
    </div>
    </div>

    @if(\Auth::guard('admin')->check())
        @include('Dashboard.layouts.main-sidebar.admin-sidebar-main')


    @elseif(\Auth::guard('employee')->check())

            @include('Dashboard.layouts.main-sidebar.employee-sidebar-main')

      @elseif(\Auth::guard()->check())

        @include('Dashboard.layouts.main-sidebar.user-sidebar-main')

    @endif

</aside>
<!-- main-sidebar -
