

// Modal: Tambah Data
function openCreateModal() {
    $('#formSekolah')[0].reset();
    $('#id').val('');
    $('#modalFormLabel').text('Tambah Sekolah');
    $('#modalForm').modal('show');
}

// Modal: Edit Data
function openEditModal(item) {
    $('#id').val(item.id);
    $('#namaWilayah').val(item.namaWilayah);
    $('#namaSekolah').val(item.namaSekolah);
    $('#namaTitik').val(item.namaTitik);
    $('#link').val(item.link);

    $('#modalFormLabel').text('Edit Sekolah');
    $('#modalForm').modal('show');
}

$(document).ready(function () {
    // Handle form submit
    $('#formSekolah').on('submit', function (e) {
        e.preventDefault();

        const id = $('#id').val();
        const url = id ? `/api/cctv-sekolah/${id}` : '/api/cctv-sekolah';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            data: {
                namaWilayah: $('#namaWilayah').val(),
                namaSekolah: $('#namaSekolah').val(),
                namaTitik: $('#namaTitik').val(),
                link: $('#link').val(),
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire('Sukses', response.message, 'success').then(() => {
                        location.reload();
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
function deleteSekolah(id) {
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
                url: `/api/cctv-sekolah/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            location.reload();
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
$(document).ready(function () {
    $('#search').on('keyup', function () {
        const searchText = $(this).val().toLowerCase();

        $('table tbody tr').each(function () {
            const rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(searchText) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
