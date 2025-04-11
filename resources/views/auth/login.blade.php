<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('skydash/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('skydash/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('skydash/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('skydash/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('skydash/images/favicon.png') }}" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="{{ asset('skydash/images/logo.svg') }}" alt="logo">
                            </div>
                            <h4>Hello! Selamat Datang !!!</h4>
                            <h6 class="font-weight-light">Login Guyssss.</h6>
                            <form class="pt-3" id="loginForm">
                                {{-- @csrf --}}
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-lg" id="email"
                                        placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" id="password"
                                        placeholder="Password" required>
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN
                                        IN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('skydash/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('skydash/js/off-canvas.js') }}"></script>
    <script src="{{ asset('skydash/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('skydash/js/template.js') }}"></script>
    <script src="{{ asset('skydash/js/settings.js') }}"></script>
    <script src="{{ asset('skydash/js/todolist.js') }}"></script>
    <!-- endinject -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch("{{ url('/api/login') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        email,
                        password
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Simpan token di localStorage (jika dibutuhkan untuk API selanjutnya)
                    localStorage.setItem('token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));

                    // SweetAlert sukses login
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil!',
                        text: 'Selamat datang 👋',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    // Redirect ke dashboard
                    setTimeout(() => {
                        window.location.href = "{{ route('dashboard') }}";
                    }, 1600);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal!',
                        text: data.message || 'Email atau password salah'
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat login'
                });
            }
        });
    </script>

</body>

</html>
