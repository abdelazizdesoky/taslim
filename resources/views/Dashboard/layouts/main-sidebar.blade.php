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

    @if(Auth::guard('admin')->check())
    @php $permission = Auth::guard('admin')->user()->permission; @endphp
    
    @if($permission == 1)
        @include('Dashboard.layouts.main-sidebar.admin-sidebar-main')
    @elseif($permission == 2)
        @include('Dashboard.layouts.main-sidebar.user-sidebar-main')
    @elseif($permission == 3 )
        @include('Dashboard.layouts.main-sidebar.employee-sidebar-main')
    @elseif($permission == 4 )
        @include('Dashboard.layouts.main-sidebar.employee-sidebar-main')
    @endif

@endif

</aside>
<!-- main-sidebar -
