<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle" src="{{ asset('build/assets') }}/img/profile_small.jpg" />
                    <div class="dropdown-toggle">
                        <span class="block m-t-xs font-bold text-white">Hai, {{ explode(" ", auth()->user()->name)[0] }}..</span>
                        <span class="text-muted text-xs block">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>
                <div class="logo-element">
                    DA
                </div>
            </li>

            <li class="{{( request()->routeIs('dashboard')) ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dasbor</span></a>
            </li>

            <li class="{{( request()->routeIs('service.index')) ? 'active' : '' }}">
                <a href="{{ route('service.index') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Service</span></a>
            </li>

            <li class="{{( request()->routeIs('deliver.index')) ? 'active' : '' }}">
                <a href="{{ route('deliver.index') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Pengiriman</span></a>
            </li>

            <li class="{{(
                request()->routeIs('users.index') OR 
                request()->routeIs('address.index')) ? 'active' : '' }}">
                <a href="{{ route('users.index') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Pengguna</span></a>
            </li>

            <li class="special_link">
                <a href="javascript:void(0)" id="logout"><i class="fa fa-sign-out"></i> <span class="nav-label">Keluar</span></a>
            </li>
        </ul>

    </div>
</nav>