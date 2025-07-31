const token = Cookies.get("token");
if (!token) {
    window.location.href = "/login";
}

let map;
let marker;
let sekolahData = [];
let filteredData = [];
let currentPage = 1;
const itemsPerPage = 10;

const wilayahMapping = {
    1: "KAB BANTUL",
    2: "KAB SLEMAN",
    3: "KAB GUNUNG KIDUL",
    4: "KAB KULON PROGO",
    5: "KOTA YOGYAKARTA",
};

function loadSekolahData() {
    fetch("/api/nama-sekolah", {
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            sekolahData = data.data;
            filteredData = sekolahData;
            currentPage = 1;
            renderTablePage(currentPage);
        })
        .catch((error) => {
            console.error("Gagal memuat data nama sekolah:", error);
            Swal.fire("Error", "Gagal memuat data nama sekolah.", "error");
        });
}

function groupSekolahData(data) {
    const grouped = {};
    data.forEach((item) => {
        const key = `${item.wilayah_id}||${item.nama}`;
        if (!grouped[key]) {
            grouped[key] = {
                wilayah_id: item.wilayah_id,
                nama: item.nama,
                sekolah: [],
            };
        }
        grouped[key].sekolah.push(item);
    });
    return Object.values(grouped).sort((a, b) => {
        const aKey = `${a.wilayah_id}-${a.nama}`;
        const bKey = `${b.wilayah_id}-${b.nama}`;
        return aKey.localeCompare(bKey);
    });
}

function renderTablePage(page = 1) {
    const tbody = document.getElementById("sekolahTableBody");
    tbody.innerHTML = "";

    const grouped = groupSekolahData(filteredData);
    const start = (page - 1) * itemsPerPage;
    const pageData = grouped.slice(start, start + itemsPerPage);

    if (pageData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td class="text-center" colspan="5">Data belum tersedia</td>
            </tr>
        `;
        return;
    }

    pageData.forEach((group) => {
        const wilayahNama = wilayahMapping[group.wilayah_id] || "Unknown";

        // ✅ Hitung jumlah namaTitik unik
        const jumlah = group.jumlah_namaTitik || 0;

        const item = group.sekolah[0]; // perwakilan untuk edit/hapus
        const lokasi = item?.lokasi || "";

        const lokasiDisplay =
            lokasi.trim() !== ""
                ? `<a href="${lokasi}" target="_blank" style="color: blue; text-decoration: none;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">
                    Lihat Lokasi
                   </a>`
                : '<span class="text-danger">Lokasi belum tersedia</span>';

        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="text-center">${wilayahNama}</td>
            <td class="text-center">${group.nama}</td>
            <td class="text-center">${lokasiDisplay}</td>
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

function openCreateModal() {
    $("#formSekolah")[0].reset();
    $("#id").val("");
    $("#modalFormLabel").text("Tambah Sekolah DIY");
    $("#modalForm").modal("show");
}

function openEditModal(item) {
    $("#id").val(item.id);
    $("#wilayah_id").val(item.wilayah_id);
    $("#nama").val(item.nama);
    $("#lokasi").val(item.lokasi);
    $("#modalFormLabel").text("Edit Sekolah DIY");
    $("#modalForm").modal("show");

    // Tampilkan marker dari lokasi URL jika tersedia
    if (
        item.lokasi &&
        item.lokasi.includes("mlat=") &&
        item.lokasi.includes("mlon=")
    ) {
        const urlParams = new URLSearchParams(item.lokasi.split("?")[1]);
        const lat = parseFloat(urlParams.get("mlat"));
        const lng = parseFloat(urlParams.get("mlon"));

        if (!isNaN(lat) && !isNaN(lng)) {
            const latlng = L.latLng(lat, lng);
            marker.setLatLng(latlng).addTo(map);
            map.setView(latlng, 17);
            document.getElementById("lokasi").value = item.lokasi;
            document.getElementById("lokasi-info").innerText =
                "Lokasi terpilih: " + item.lokasi;
        }
    }
}

function goToPage(page) {
    currentPage = page;
    renderTablePage(page);
}

function searchcctvsekolah() {
    const q = document.getElementById("searchInput").value.trim().toLowerCase();
    filteredData = sekolahData.filter(
        (item) =>
            (wilayahMapping[item.wilayah_id] || "").toLowerCase().includes(q) ||
            (item.nama || "").toLowerCase().includes(q)
    );
    currentPage = 1;
    renderTablePage();
}

function applyFilters() {
    const searchValue = document
        .getElementById("searchInput")
        .value.toLowerCase();
    const wilayahValue = document
        .getElementById("filterWilayah")
        .value.toLowerCase();
    const kategoriValue = document
        .getElementById("filterKategori")
        .value.toLowerCase(); // "sma" atau "smk"

    filteredData = sekolahData.filter((item) => {
        const wilayah = (wilayahMapping[item.wilayah_id] || "").toLowerCase();
        const namaSekolah = (item.nama || "").toLowerCase(); // 🔁 perbaikan di sini

        const matchSearch =
            namaSekolah.includes(searchValue) || wilayah.includes(searchValue);

        const matchWilayah = !wilayahValue || wilayah === wilayahValue;

        let matchKategori = true;
        if (kategoriValue === "sma") {
            matchKategori = namaSekolah.includes("sma");
        } else if (kategoriValue === "smk") {
            matchKategori = namaSekolah.includes("smk");
        }

        return matchSearch && matchWilayah && matchKategori;
    });

    renderTablePage(1);
}

function updateLocationInput(latlng) {
    const lat = latlng.lat.toFixed(6);
    const lng = latlng.lng.toFixed(6);
    const url = `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=17/${lat}/${lng}`;
    document.getElementById("lokasi").value = url;
    document.getElementById("lokasi-info").innerText =
        "Lokasi terpilih: " + url;
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
                url: `/api/nama-sekolah/${id}`,
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

// Inisialisasi map dan event listener
document.addEventListener("DOMContentLoaded", function () {
    loadSekolahData();

    const input = document.getElementById("searchInput");
    if (input) input.addEventListener("keyup", searchcctvsekolah);

    map = L.map("map").setView([-7.801194, 110.364917], 13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap contributors",
    }).addTo(map);

    L.Control.geocoder({
        defaultMarkGeocode: true,
    })
        .on("markgeocode", function (e) {
            const latlng = e.geocode.center;
            marker.setLatLng(latlng);
            map.setView(latlng, 17);
            updateLocationInput(latlng);
        })
        .addTo(map);

    marker = L.marker([0, 0], { draggable: true }).addTo(map);

    map.on("click", function (e) {
        marker.setLatLng(e.latlng);
        updateLocationInput(e.latlng);
    });

    marker.on("dragend", function (e) {
        updateLocationInput(e.target.getLatLng());
    });
});

$("#formSekolah").on("submit", function (e) {
    e.preventDefault();
    const id = $("#id").val();
    const url = id ? `/api/nama-sekolah/${id}` : "/api/nama-sekolah";
    const method = id ? "PUT" : "POST";

    const wilayah_id = $("#wilayah_id").val().trim();
    const nama = $("#nama").val().trim();
    const lokasi = $("#lokasi").val().trim();

    if (!wilayah_id || !nama || !lokasi) {
        Swal.fire("Peringatan", "Semua field wajib diisi.", "warning");
        return;
    }

    Swal.fire({
        title: "Menyimpan...",
        text: "Mohon tunggu sebentar",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    $.ajax({
        url,
        type: method,
        headers: {
            Authorization: "Bearer " + token,
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        data: { wilayah_id, nama, lokasi },
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
