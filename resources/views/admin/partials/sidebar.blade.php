<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .menu-icon-wrapper {
            width: 24px;
            display: flex;
            justify-content: center;
        }
    </style>

    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <div class="menu-icon-wrapper">
                    <i class="fa-solid fa-house menu-icon" style="font-size: 12pt;"></i>
                </div>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <div class="menu-icon-wrapper">
                    <i class="fa-solid fa-user menu-icon" style="font-size: 12pt;"></i>
                </div>
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
            <a class="nav-link {{ request()->is('sekolah*') || request()->is('panorama*') ? '' : 'collapsed' }}"
                data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <div class="menu-icon-wrapper">
                    <i class="fas fa-bookmark menu-icon" style="font-size: 12pt;"></i>
                </div>
                <span class="menu-title">Kelola Data</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('sekolah*') || request()->is('panorama*') ? 'show' : '' }}"
                id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('sekolah*') ? 'active' : '' }}"
                            href="{{ route('sekolah.index') }}">CCTV Sekolah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('panorama*') ? 'active' : '' }}"
                            href="{{ route('panorama.index') }}">CCTV Panorama</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>