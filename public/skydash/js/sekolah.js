const token = Cookies.get("token");
if (!token) {
    window.location.href = "/login";
}

let sekolahData = [];
let filteredData = [];
let currentPage = 1;
const itemsPerPage = 5;

const wilayahMapping = {
    1: "KAB BANTUL",
    2: "KAB SLEMAN",
    3: "KAB GUNUNG KIDUL",
    4: "KAB KULON PROGO",
    5: "KOTA YOGYAKARTA",
};

function loadSekolahData() {
    fetch("/api/cctv-sekolah", {
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            sekolahData = data.data;
            filteredData = sekolahData; // simpan data asli
            currentPage = 1;
            renderTablePage(currentPage);
        })
        .catch((error) => {
            console.error("Gagal memuat data sekolah:", error);
            Swal.fire("Error", "Gagal memuat data sekolah.", "error");
        });
}

function groupSekolahData(data) {
    const grouped = {};
    data.forEach((item) => {
        const key = `${item.wilayah_id}||${item.namaSekolah}`;
        if (!grouped[key]) {
            grouped[key] = {
                wilayah_id: item.wilayah_id,
                namaSekolah: item.namaSekolah,
                titik: [],
            };
        }
        grouped[key].titik.push(item);
    });
    return Object.values(grouped).sort((a, b) => {
        const aKey = `${a.wilayah_id}-${a.namaSekolah}`;
        const bKey = `${b.wilayah_id}-${b.namaSekolah}`;
        return aKey.localeCompare(bKey);
    });
}

function renderTablePage(page = 1) {
    const tbody = document.getElementById("sekolahTableBody");
    tbody.innerHTML = "";

    const grouped = groupSekolahData(filteredData);
    const start = (page - 1) * itemsPerPage;
    const pageData = grouped.slice(start, start + itemsPerPage);

    pageData.forEach((group) => {
        const wilayahNama = wilayahMapping[group.wilayah_id] || "Unknown";
        const rowspan = group.titik.length;

        group.titik.forEach((item, index) => {
            const row = document.createElement("tr");
            const statusClass =
                item.status === "online" ? "text-success" : "text-danger";

            row.innerHTML = `
        ${
            index === 0
                ? `<td rowspan="${rowspan}" class="align-middle text-center">${wilayahNama}</td>`
                : ""
        }
        ${
            index === 0
                ? `<td rowspan="${rowspan}" class="align-middle text-center">${group.namaSekolah}</td>`
                : ""
        }
        <td class="text-center">${item.namaTitik}</td>
        <td class="text-center ${statusClass}">${item.status}</td>
        <td class="text-center">
            <div style="display: flex; gap: 0.3rem; justify-content: center;">
                <button class="btn btn-sm btn-primary" onclick='openEditModal(${JSON.stringify(
                    item
                )})'>Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteSekolah(${
                    item.id
                })">Delete</button>
            </div>
        </td>
    `;
            tbody.appendChild(row);
        });
    });

    renderPagination(grouped.length);
}

function renderPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
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

function goToPage(page) {
    currentPage = page;
    renderTablePage(page);
}

function openCreateModal() {
    $("#formSekolah")[0].reset();
    $("#id").val("");
    $("#modalFormLabel").text("Tambah CCTV Sekolah");
    $("#modalForm").modal("show");
}

function openEditModal(item) {
    $("#id").val(item.id);
    $("#wilayah_id").val(item.wilayah_id);
    $("#namaSekolah").val(item.namaSekolah);
    $("#namaTitik").val(item.namaTitik);
    $("#link").val(item.link);
    $("#modalFormLabel").text("Edit CCTV Sekolah");
    $("#modalForm").modal("show");
}

function deleteSekolah(id) {
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
                url: `/api/cctv-sekolah/${id}`,
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
                                loadSekolahData();
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

// ✅ FIXED: Pencarian lokal berbasis filteredData
function searchcctvsekolah() {
    const q = document.getElementById("searchInput").value.trim().toLowerCase();
    filteredData = sekolahData.filter(
        (item) =>
            (wilayahMapping[item.wilayah_id] || "").toLowerCase().includes(q) ||
            (item.namaSekolah || "").toLowerCase().includes(q) ||
            (item.namaTitik || "").toLowerCase().includes(q)
    );
    currentPage = 1;
    renderTablePage();
}

// ✅ Trigger pencarian saat diketik
document.addEventListener("DOMContentLoaded", function () {
    loadSekolahData();
    const input = document.getElementById("searchInput");
    if (input) input.addEventListener("keyup", searchcctvsekolah);
});

$("#formSekolah").on("submit", function (e) {
    e.preventDefault();
    const id = $("#id").val();
    const url = id ? `/api/cctv-sekolah/${id}` : "/api/cctv-sekolah";
    const method = id ? "PUT" : "POST";

    const wilayah_id = $("#wilayah_id").val().trim();
    const namaSekolah = $("#namaSekolah").val().trim();
    const namaTitik = $("#namaTitik").val().trim();
    const link = $("#link").val().trim();

    if (!wilayah_id || !namaSekolah || !namaTitik || !link) {
        Swal.fire("Peringatan", "Semua field wajib diisi.", "warning");
        return;
    }

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
        data: {
            wilayah_id,
            namaSekolah,
            namaTitik,
            link,
        },
        success: function (response) {
            if (response.success) {
                Swal.fire("Sukses", response.message, "success").then(() => {
                    $("#modalForm").modal("hide");
                    loadSekolahData();
                });
            } else {
                Swal.fire("Gagal", response.message, "error");
            }
        },
        error: function (xhr) {
            Swal.fire(
                "Error",
                "Terjadi kesalahan saat mengirim data.",
                "error"
            );
            console.error(xhr.responseText);
        },
    });
});
