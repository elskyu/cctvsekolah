let sekolahData = [];
let filteredData = [];
let currentPage = 1;
const itemsPerPage = 10;

// Mapping wilayah jika diperlukan
const wilayahMapping = {
    1: "KAB BANTUL",
    2: "KAB SLEMAN",
    3: "KAB GUNUNG KIDUL",
    4: "KAB KULON PROGO",
    5: "KOTA YOGYAKARTA",
};

// Ambil data dari API
async function fetchDataFromApi() {
    try {
        const response = await fetch("/api/cctv-offline");
        const result = await response.json();

        if (result.success) {
            // Urutkan dari yang terbaru berdasarkan 'offline_since' (atau 'date')
            sekolahData = result.data.sort((a, b) => {
                const dateA = new Date(a.offline_since || a.date);
                const dateB = new Date(b.offline_since || b.date);
                return dateB - dateA; // descending
            });

            applyFilters();
        } else {
            console.error("Gagal ambil data:", result.message);
        }
    } catch (error) {
        console.error("Error saat ambil data:", error);
    }
}

// Render tabel berdasarkan halaman
function renderRekapTablePage(page = 1) {
    const tbody = document.getElementById("rekapTableBody");
    tbody.innerHTML = "";

    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = filteredData.slice(start, end);

    if (pageData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center">Tidak ada data offline</td></tr>`;
    } else {
        for (const row of pageData) {
            const lastSeen =
                !row.last_seen ||
                row.last_seen === "0000-00-00 00:00:00" ||
                row.last_seen === "null"
                    ? `<span class="text-danger">Belum pernah online</span>`
                    : `<span class="text-success">${row.last_seen}</span>`;

            tbody.innerHTML += `
                <tr>
                    <td>${
                        wilayahMapping[row.wilayah_id] || "Tidak diketahui"
                    }</td>
                    <td>${row.namaSekolah}</td>
                    <td>${row.namaTitik}</td>
                    <td>${lastSeen}</td>
                    <td>${row.offline_since}</td>
                    <td>${row.date}</td>
                </tr>
            `;
        }
    }

    renderPagination(filteredData.length);
}

function renderPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    const maxVisiblePages = 10;

    let startPage = Math.max(1, currentPage - 4);
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    // Jika terlalu dekat dengan akhir, sesuaikan startPage
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    // Tombol Previous
    if (currentPage > 1) {
        pagination.innerHTML += `
            <li class="page-item">
                <button class="page-link" onclick="goToPage(${
                    currentPage - 1
                })">&laquo;</button>
            </li>
        `;
    }

    // Tampilkan halaman awal jika belum muncul
    if (startPage > 1) {
        pagination.innerHTML += `
            <li class="page-item">
                <button class="page-link" onclick="goToPage(1)">1</button>
            </li>
            <li class="page-item disabled"><span class="page-link">...</span></li>
        `;
    }

    // Halaman yang ditampilkan
    for (let i = startPage; i <= endPage; i++) {
        pagination.innerHTML += `
            <li class="page-item ${i === currentPage ? "active" : ""}">
                <button class="page-link" onclick="goToPage(${i})">${i}</button>
            </li>
        `;
    }

    // Tambahkan "..." dan halaman terakhir
    if (endPage < totalPages) {
        pagination.innerHTML += `
            <li class="page-item disabled"><span class="page-link">...</span></li>
            <li class="page-item">
                <button class="page-link" onclick="goToPage(${totalPages})">${totalPages}</button>
            </li>
        `;
    }

    // Tombol Next
    if (currentPage < totalPages) {
        pagination.innerHTML += `
            <li class="page-item">
                <button class="page-link" onclick="goToPage(${
                    currentPage + 1
                })">&raquo;</button>
            </li>
        `;
    }
}

// Navigasi ke halaman lain
function goToPage(page) {
    currentPage = page;
    renderRekapTablePage(currentPage);
}

// Jalankan saat dokumen siap
document.addEventListener("DOMContentLoaded", () => {
    fetchDataFromApi();
});

// Ambil data dari API
async function fetchDataFromApi() {
    try {
        const response = await fetch("/api/cctv-offline");
        const result = await response.json();

        if (result.success) {
            sekolahData = result.data;
            applyFilters();
        } else {
            console.error("Gagal ambil data:", result.message);
        }
    } catch (error) {
        console.error("Error saat ambil data:", error);
    }
}

// Terapkan filter dan pencarian
function applyFilters() {
    const range = document.getElementById("rangeFilter").value;
    const wilayahId = document.getElementById("wilayahFilter").value;
    const kategori = document
        .getElementById("kategoriFilter")
        .value.toUpperCase();
    const search = document.getElementById("searchInput").value.toLowerCase();

    const today = new Date();

    filteredData = sekolahData.filter((item) => {
        const itemDate = new Date(item.date);

        // Filter waktu
        let matchRange = true;
        if (range === "daily") {
            matchRange = itemDate.toDateString() === today.toDateString();
        } else if (range === "weekly") {
            const oneWeekAgo = new Date();
            oneWeekAgo.setDate(today.getDate() - 7);
            matchRange = itemDate >= oneWeekAgo;
        } else if (range === "monthly") {
            const oneMonthAgo = new Date();
            oneMonthAgo.setDate(today.getDate() - 30);
            matchRange = itemDate >= oneMonthAgo;
        }

        // Filter wilayah berdasarkan wilayah_id
        const matchWilayah = wilayahId === "" || item.wilayah_id == wilayahId;

        // Filter kategori
        const matchKategori =
            kategori === "" ||
            item.namaSekolah.toUpperCase().startsWith(kategori);

        // Filter search (nama sekolah / titik)
        const matchSearch =
            search === "" ||
            item.namaSekolah.toLowerCase().includes(search) ||
            item.namaTitik.toLowerCase().includes(search);

        return matchRange && matchWilayah && matchKategori && matchSearch;
    });

    currentPage = 1;
    renderRekapTablePage(currentPage);
}

// Tombol export PDF (optional redirect)
document.getElementById("exportBtn").addEventListener("click", (e) => {
    e.preventDefault();

    const params = new URLSearchParams({
        range: document.getElementById("rangeFilter").value,
        wilayah: document.getElementById("wilayahFilter").value,
        kategori: document.getElementById("kategoriFilter").value,
        search: document.getElementById("searchInput").value,
    });

    window.location.href = `/export/cctv-offline?${params.toString()}`;
});

// Event listeners
document.getElementById("rangeFilter").addEventListener("change", applyFilters);
document
    .getElementById("wilayahFilter")
    .addEventListener("change", applyFilters);
document
    .getElementById("kategoriFilter")
    .addEventListener("change", applyFilters);
document.getElementById("searchInput").addEventListener("input", applyFilters);

// Awal muat data
document.addEventListener("DOMContentLoaded", () => {
    fetchDataFromApi();
});
