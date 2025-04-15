document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async function (e) {
            e.preventDefault();

            const token = Cookies.get('token');

            if (!token) {
                window.location.replace("/login");
                return;
            }

            try {
                const response = await fetch("/api/logout", {
                    method: "POST",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json"
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    Cookies.remove('token');
                    Cookies.remove('user');

                    Swal.fire({
                        icon: 'success',
                        title: 'Logout berhasil!',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    setTimeout(() => {
                        window.location.replace("/login");
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
