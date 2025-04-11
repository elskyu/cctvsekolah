<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    {{-- Logo --}}
    @include('admin.components.nav-logo')

    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <img src="{{ asset('skydash/images/faces/face28.jpg') }}" alt="profile" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="#" id="logoutBtn">
                        <i class="ti-power-off text-primary"></i>
                        Logout
                    </a>
                </div>
            </li>
            <li class="nav-item nav-settings d-none d-lg-flex">
                <a class="nav-link" href="#">
                    <i class="icon-ellipsis"></i>
                </a>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', async function (e) {
                e.preventDefault();
                const token = localStorage.getItem('token');

                if (!token) {
                    window.location.href = "{{ url('/login') }}";
                    return;
                }

                try {
                    const response = await fetch("{{ url('/api/logout') }}", {
                        method: "POST",
                        headers: {
                            "Authorization": "Bearer " + token,
                            "Accept": "application/json"
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        localStorage.removeItem('token');
                        localStorage.removeItem('user');

                        // Tampilkan SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Logout berhasil!',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        setTimeout(() => {
                            window.location.href = "{{ url('/login') }}";
                        }, 1600);
                    } else {
                        throw new Error(data.message || 'Gagal logout');
                    }
                } catch (error) {
                    console.error("Logout error:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Logout gagal!',
                        text: error.message
                    });
                }
            });
        }
    });
</script>

