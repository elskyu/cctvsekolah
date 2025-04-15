const token = Cookies.get('token');

if (!token) {
    // Redirect ke halaman login kalau token tidak ada
    window.location.href = '/login'; 
}

// Modal: Tambah Data
function openCreateModal() {
    $('#formPanorama')[0].reset(); // kosongkan form
    $('#id').val(''); // pastikan ID kosong (tambah mode)
    $('#modalFormLabel').text('Tambah CCTV Panorama'); // ubah judul modal
    $('#modalForm').modal('show'); // tampilkan modal
}


// Modal: Edit Data
function openEditModal(item) {
    $('#id').val(item.id);
    $('#namaWilayah').val(item.namaWilayah);
    $('#namaTitik').val(item.namaTitik);
    $('#link').val(item.link);

    $('#modalFormLabel').text('Edit CCTV Panorama');
    $('#modalForm').modal('show');
}

function loadPanoramaData() {
    fetch('/api/cctv-panorama', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        panoramaData = data.data;
        currentPage = 1;
        renderTablePage(currentPage);
    })
    .catch(error => {
        console.error('Gagal memuat data panorama:', error);
        Swal.fire('Error', 'Gagal memuat data panorama.', 'error');
    });
}


let panoramaData = []; // global variable untuk nyimpen data asli
let currentPage = 1;
const itemsPerPage = 5;

function renderTablePage(page) {
    const tbody = document.getElementById('panoramaTableBody');
    tbody.innerHTML = '';

    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = panoramaData.slice(start, end);

    pageData.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.namaWilayah}</td>
            <td>${item.namaTitik}</td>
            <td>
                <div style="display: flex; gap: 5px;">
                    <button class="btn btn-sm btn-primary" onclick='openEditModal(${JSON.stringify(item)})'>Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deletepanorama(${item.id})">Delete</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });

    renderPagination();
}

function renderPagination() {
    const totalPages = Math.ceil(panoramaData.length / itemsPerPage);
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
    loadPanoramaData();
    // Handle form submit
    $('#formPanorama').on('submit', function (e) {
        e.preventDefault();

        const id = $('#id').val();
        const url = id ? `/api/cctv-panorama/${id}` : '/api/cctv-panorama';
        const method = id ? 'PUT' : 'POST';

        const namaWilayah = $('#namaWilayah').val().trim();
        const namaTitik = $('#namaTitik').val().trim();
        const link = $('#link').val().trim();

        if (!namaWilayah || !namaTitik || !link) {
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

        $.ajax({
            url: url,
            type: method,
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            data:   {
                    namaWilayah,
                    namaTitik,
                    link,
                    },

            success: function (response) {
                if (response.success) {
                    Swal.fire('Sukses', response.message, 'success').then(() => {
                        $('#modalForm').modal('hide');
                        loadPanoramaData();
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
function deletepanorama(id) {
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
                url: `/api/cctv-panorama/${id}`,
                type: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            loadPanoramaData();
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

    const filtered = panoramaData.filter(item =>
        item.namaWilayah.toLowerCase().includes(searchText) ||
        item.namaTitik.toLowerCase().includes(searchText)
    );

    currentPage = 1;
    renderFilteredTable(filtered);
});

function renderFilteredTable(filteredData) {
    const tbody = document.getElementById('panoramaTableBody');
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
                    <button class="btn btn-sm btn-danger" onclick="deletepanorama(${item.id})">Delete</button>
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
