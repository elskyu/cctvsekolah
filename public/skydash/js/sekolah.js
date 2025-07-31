const token = Cookies.get("token");
if (!token) {
    window.location.href = "/login";
}

let map;
let marker;
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

document.getElementById("wilayah_id").addEventListener("change", function () {
    const wilayahId = this.value;
    populateSekolahDropdown(wilayahId);
});

function populateSekolahDropdown(wilayahId) {
    fetch("/api/nama-sekolah", {
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json",
        },
    })
        .then((res) => res.json())
        .then((data) => {
            const sekolahSelect = document.getElementById("sekolah_id");
            sekolahSelect.innerHTML = '<option value="">Pilih Sekolah</option>';

            data.data
                .filter((item) => item.wilayah_id == wilayahId)
                .forEach((item) => {
                    sekolahSelect.innerHTML += `<option value="${item.id}" data-latlng="${item.lokasi}">${item.nama}</option>`;
                });
        })
        .catch((err) => {
            console.error("Gagal mengambil data sekolah:", err);
        });
}

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

            // ✅ Cek apakah lokasi berisi URL
            const lokasiDisplay =
                item.lokasi && item.lokasi.trim() !== ""
                    ? `<a href="${item.lokasi}" target="_blank" style="color: blue; text-decoration: none;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">
                Lihat Lokasi
                    </a>`
                    : '<span class="text-danger">Lokasi belum tersedia</span>';

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
    ${
        index === 0
            ? `<td rowspan="${rowspan}" class="align-middle text-center">${rowspan}</td>`
            : ""
    }
    <td class="text-center">${item.namaTitik}</td>
    <td class="text-center">${lokasiDisplay}</td>
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

    populateSekolahDropdown(item.wilayah_id, () => {
        $("#sekolah_id").val(item.nama_sekolah_id); // setelah options dimuat
        $("#namaSekolah").val($("#sekolah_id option:selected").text().trim()); // set hidden input juga

        // Jika lokasi ingin diarahkan ke peta:
        const selectedOption = $("#sekolah_id option:selected")[0];
        const latlng = extractLatLngFromUrl(selectedOption.dataset.latlng);
        if (latlng && map && marker) {
            map.setView(latlng, 17);
            marker.setLatLng(latlng);
            updateLocationInput(latlng);
        }
    });

    $("#namaTitik").val(item.namaTitik);
    $("#lokasi").val(item.lokasi);
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
        const namaSekolah = (item.namaSekolah || "").toLowerCase();

        const matchSearch =
            namaSekolah.includes(searchValue) || wilayah.includes(searchValue);

        const matchWilayah = !wilayahValue || wilayah === wilayahValue;

        let matchKategori = true;
        if (kategoriValue === "sma") {
            matchKategori = namaSekolah.includes("sman");
        } else if (kategoriValue === "smk") {
            matchKategori = namaSekolah.includes("smkn");
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
    const sekolah_id = $("#sekolah_id").val().trim();
    const namaTitik = $("#namaTitik").val().trim();
    const lokasi = $("#lokasi").val().trim();
    const link = $("#link").val().trim();

    // Ambil nama sekolah dari dropdown text (untuk disimpan ke kolom namaSekolah)
    const namaSekolah = $("#sekolah_id option:selected").text().trim();

    if (
        !wilayah_id ||
        !sekolah_id ||
        !namaSekolah ||
        !namaTitik ||
        !lokasi ||
        !link
    ) {
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
            nama_sekolah_id: sekolah_id, // dikirim ke DB
            namaSekolah, // disimpan di kolom namaSekolah
            namaTitik,
            lokasi,
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

document.getElementById("sekolah_id").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const sekolahName = selectedOption.textContent;

    // Isi hidden input namaSekolah
    document.getElementById("namaSekolah").value = sekolahName;

    // Ambil latlng dari data-latlng dan arahkan peta
    const latlngData = extractLatLngFromUrl(selectedOption.dataset.latlng);
    if (latlngData && map && marker) {
        map.setView(latlngData, 17);
        marker.setLatLng(latlngData);
        updateLocationInput(latlngData);
    }
});

function extractLatLngFromUrl(url) {
    try {
        const match = url.match(/mlat=([-.\d]+)&mlon=([-.\d]+)/);
        if (match) {
            return {
                lat: parseFloat(match[1]),
                lng: parseFloat(match[2]),
            };
        }
    } catch (e) {
        console.warn("Gagal parsing lokasi:", url);
    }
    return null;
}
