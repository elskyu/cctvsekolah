let UsersData = [];
let filteredData = [];
let currentPage = 1;
const itemsPerPage = 10;

const token = Cookies.get("token");

// 1. Ambil data dari API
function loadUsersData() {
    fetch("/api/users", {
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json",
        },
    })
        .then((res) => res.json())
        .then((json) => {
            if (!json.success) {
                return Swal.fire("Error", json.message, "error");
            }

            const sortedData = json.data.sort((a, b) =>
                (a.name || "")
                    .toLowerCase()
                    .localeCompare((b.name || "").toLowerCase())
            );

            UsersData = sortedData;
            filteredData = UsersData;
            currentPage = 1;
            renderTable();
        })
        .catch((err) => {
            console.error(err);
            Swal.fire("Error", "Gagal memuat data.", "error");
        });
}

// 2. Render tabel berdasarkan filteredData & paging
function renderTable() {
    const tbody = document.getElementById("usersTableBody");
    tbody.innerHTML = "";

    const start = (currentPage - 1) * itemsPerPage;
    const pageData = filteredData.slice(start, start + itemsPerPage);

    pageData.forEach((item) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="text-center">${item.name}</td>
            <td class="text-center">${item.email}</td>
            <td class="text-center">${item.phone}</td>
            <td>
                <div style="display: flex; gap: 0.3rem; justify-content: center;">
                    <button class="btn btn-sm btn-primary" onclick='openEditModal(${JSON.stringify(
                        item
                    )})'>Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUsers(${
                        item.id
                    })">Delete</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });

    renderPagination();
}

// 3. Render tombol pagination
function renderPagination() {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        pagination.innerHTML += `
            <li class="page-item ${i === currentPage ? "active" : ""}">
                <button class="page-link" onclick="goToPage(${i})">${i}</button>
            </li>
        `;
    }
}

// 4. Navigasi halaman
function goToPage(page) {
    currentPage = page;
    renderTable();
}

// 5. Search lokal (live search)
function searchUsers() {
    const q = document.getElementById("searchInput").value.trim().toLowerCase();
    filteredData = UsersData.filter(
        (item) =>
            (item.name || "").toLowerCase().includes(q) ||
            (item.email || "").toLowerCase().includes(q) ||
            (item.phone || "").toLowerCase().includes(q)
    );
    currentPage = 1;
    renderTable();
}

// 6. Tambah data
function openCreateModal() {
    $("#formUsers")[0].reset();
    $("#id").val("");
    $("#modalFormLabel").text("Tambah Pengguna");
    $("#password").prop("required", true);
    $("#modalForm").modal("show");
}

// 7. Edit data
function openEditModal(item) {
    $("#id").val(item.id);
    $("#nama").val(item.name);
    $("#email").val(item.email);
    $("#password").val("");
    $("#phone").val(item.phone);
    $("#modalFormLabel").text("Edit Pengguna");
    $("#password").prop("required", false);
    $("#modalForm").modal("show");
}

// 8. Hapus data
function deleteUsers(id) {
    Swal.fire({
        title: "Yakin hapus?",
        text: "Data akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/api/users/${id}`,
                type: "DELETE",
                headers: {
                    Authorization: "Bearer " + token,
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire("Berhasil", response.message, "success").then(
                            () => {
                                loadUsersData();
                            }
                        );
                    } else {
                        Swal.fire("Gagal", response.message, "error");
                    }
                },
                error: function (xhr) {
                    Swal.fire("Error", "Gagal menghapus data.", "error");
                    console.error(xhr.responseText);
                },
            });
        }
    });
}

// 9. Submit Tambah/Edit
$("#formUsers").on("submit", function (e) {
    e.preventDefault();

    const id = $("#id").val();
    const url = id ? `/api/users/${id}` : "/api/users";
    const method = id ? "PUT" : "POST";

    const name = $("#nama").val().trim();
    const email = $("#email").val().trim();
    const password = $("#password").val().trim();
    const phone = $("#phone").val().trim();

    if (!name || !email || !phone) {
        Swal.fire("Peringatan", "Semua field wajib diisi.", "warning");
        return;
    }

    let data = { name, email, phone };
    if (password) data.password = password;

    Swal.fire({
        title: "Menyimpan...",
        text: "Mohon tunggu sebentar",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    $.ajax({
        url: url,
        type: method,
        headers: {
            Authorization: "Bearer " + token,
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        data: data,
        success: function (res) {
            if (res.success) {
                Swal.fire("Sukses", res.message, "success").then(() => {
                    $("#modalForm").modal("hide");
                    loadUsersData();
                });
            } else {
                Swal.fire("Gagal", res.message, "error");
            }
        },
        error: function (xhr) {
            Swal.fire(
                "Error",
                "Terjadi kesalahan saat menyimpan data.",
                "error"
            );
            console.error(xhr.responseText);
        },
    });
});

// 10. Inisialisasi saat dokumen siap
$(document).ready(function () {
    loadUsersData();
    $("#searchInput").on("keyup", searchUsers); // pencarian langsung
});
