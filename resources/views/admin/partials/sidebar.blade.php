<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Kelola Pengguna</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('kelola.users') }}"> Data Pengguna </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('sekolah*') || request()->is('panorama*') ? '' : 'collapsed' }}" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <i class="icon-grid-2 menu-icon"></i>
                <span class="menu-title">Kelola Data</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('sekolah*') || request()->is('panorama*') ? 'show' : '' }}" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('sekolah*') ? 'active' : '' }}" href="{{ route('sekolah.index') }}">CCTV Sekolah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('panorama*') ? 'active' : '' }}" href="{{ route('panorama.index') }}">CCTV Panorama</a>
                    </li>
                </ul>
            </div>
        </li>        
    </ul>
</nav>
