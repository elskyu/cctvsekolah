const token = Cookies.get('token');

// if (!token) {
//     // Redirect ke halaman login kalau token tidak ada
//     window.location.href = '/login';
// }

// Modal: Tambah Data
function openCreateModal() {
    $('#formUsers')[0].reset(); // kosongkan form
    $('#id').val(''); // pastikan ID kosong (tambah mode)
    $('#modalFormLabel').text('Tambah Pengguna'); // ubah judul modal
    $('#password').prop('required', true); // password wajib saat tambah
    $('#modalForm').modal('show'); // tampilkan modal
}


// Modal: Edit Data
function openEditModal(item) {
    $('#id').val(item.id);
    $('#nama').val(item.name);
    $('#email').val(item.email);
    $('#password').val('');
    $('#phone').val(item.phone);

    $('#modalFormLabel').text('Edit Pengguna');
    $('#password').prop('required', false); // password tidak wajib saat edit
    $('#modalForm').modal('show');
}

function loadusersData() {
    fetch('/api/users', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        usersData = data.data;
        currentPage = 1;
        renderTablePage(currentPage);
    })
    .catch(error => {
        console.error('Gagal memuat data users:', error);
        Swal.fire('Error', 'Gagal memuat data users.', 'error');
    });
}


let usersData = []; // global variable untuk nyimpen data asli
let currentPage = 1;
const itemsPerPage = 5;

function renderTablePage(page) {
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = '';

    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = usersData.slice(start, end);

    pageData.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.email}</td>
            <td>${item.phone}</td>
            <td>
                <div style="display: flex; gap: 5px;">
                    <button class="btn btn-sm btn-primary" onclick='openEditModal(${JSON.stringify(item)})'>Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteusers(${item.id})">Delete</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });

    renderPagination();
}

function renderPagination() {
    const totalPages = Math.ceil(usersData.length / itemsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        pagination.innerHTML += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <button class="page-link" onclick="goToPage(${i})">${i}</button>
            </li>
        `;
    }
}

function goToPage(page) {
    currentPage = page;
    renderTablePage(currentPage);
}

$(document).ready(function () {
    loadusersData();
    // Handle form submit
    $('#formUsers').on('submit', function (e) {
        e.preventDefault();

        const id = $('#id').val();
        const url = id ? `/api/users/${id}` : '/api/users';
        const method = id ? 'PUT' : 'POST';

        const name = $('#nama').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val().trim();
        const phone = $('#phone').val().trim();

        if (!name || !email || !phone) {
            Swal.fire('Peringatan', 'Semua field wajib diisi.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        let data = {
            name,
            email,
            phone,
        };

        if (password) {
            data.password = password; // kirim password hanya jika diisi
        }

        $.ajax({
            url: url,
            type: method,
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },

            data: data ,

            success: function (response) {
                if (response.success) {
                    Swal.fire('Sukses', response.message, 'success').then(() => {
                        $('#modalForm').modal('hide');
                        loadusersData();
                    });
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', 'Terjadi kesalahan saat mengirim data.', 'error');
                console.error(xhr.responseText);
            }
        });
    });
});
// Hapus Data
function deleteusers(id) {
    Swal.fire({
        title: 'Yakin hapus?',
        text: "Data akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/api/users/${id}`,
                type: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            loadusersData();
                        });
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', 'Gagal menghapus data.', 'error');
                    console.error(xhr.responseText);
                }
            });
        }
    });
}

// Fitur Live Search
$('#search').on('keyup', function () {
    const searchText = $(this).val().toLowerCase();

    const filtered = usersData.filter(item =>
        item.name.toLowerCase().includes(searchText) ||
        item.email.toLowerCase().includes(searchText) ||
        item.phone.toLowerCase().includes(searchText)
    );

    currentPage = 1;
    renderFilteredTable(filtered);
});

function renderFilteredTable(filteredData) {
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = '';

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = filteredData.slice(start, end);

    pageData.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.namaWilayah}</td>
            <td>${item.namaTitik}</td>
            <td>
                <div class="d-flex">
                    <button class="btn btn-sm btn-primary" onclick='openEditModal(${JSON.stringify(item)})'>Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteusers(${item.id})">Delete</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
    renderFilteredPagination(filteredData);
}

function renderFilteredPagination(filteredData) {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        pagination.innerHTML += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <button class="page-link" onclick="goToFilteredPage(${i}, ${JSON.stringify(filteredData)})">${i}</button>
            </li>
        `;
    }
}

function goToFilteredPage(page, filteredData) {
    currentPage = page;
    renderFilteredTable(filteredData);
}
