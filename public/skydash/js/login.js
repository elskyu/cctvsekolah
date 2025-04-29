document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
        const response = await fetch("/api/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (response.ok) {
            Cookies.set('token', data.access_token, { expires: 7, sameSite: 'Strict' });
            Cookies.set('user', JSON.stringify(data.user), { expires: 7, sameSite: 'Strict' });

            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: 'Selamat datang 👋',
                timer: 1500,
                showConfirmButton: false
            });

            setTimeout(() => {
                window.location.href = "/dashboard"; // Ganti kalau route-nya beda
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
