@extends('layouts.app')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>Dashboard CCTV</title>
    <script>
        function toggleDaerah(id) {
            const subMenu = document.getElementById(id);
            const icon = document.getElementById('icon-' + id); // ID harus match

            const isVisible = subMenu.style.display === 'block';

            subMenu.style.display = isVisible ? 'none' : 'block';
            icon.classList.toggle('fa-angle-right');
            icon.classList.toggle('fa-angle-down');
        }

        function toggleCCTV(id, checkbox) {
            const cctvContainer = document.getElementById(id);
            const iframe = cctvContainer.querySelector("iframe");

            if (checkbox.checked) {
                cctvContainer.style.display = "block";
                iframe.src = iframe.getAttribute("data-src");
            } else {
                cctvContainer.style.display = "none";
                iframe.src = "";
            }

            // Simpan status ke localStorage
            localStorage.setItem(checkbox.id, checkbox.checked);
        }

        function toggleIcon(event, namaSekolah) {
            event.stopPropagation();
            event.preventDefault();

            const icon = event.target;
            const cctvToShow = document.querySelectorAll(`.cctv-view[data-sekolah="${namaSekolah}"]`);
            const checkboxes = document.querySelectorAll(`input[data-sekolah="${namaSekolah}"]`);

            let activeSchools = JSON.parse(localStorage.getItem("activeSchools")) || [];

            // Toggle visibility berdasarkan status ikon
            if (icon.classList.contains('fa-eye')) {
                // Tampilkan CCTV untuk sekolah yang dipilih
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');

                cctvToShow.forEach(cctv => {
                    cctv.style.display = "block";
                    const iframe = cctv.querySelector("iframe");
                    if (iframe) {
                        iframe.src = iframe.getAttribute("data-src");
                    }
                });

                // Tambahkan sekolah ke daftar aktif jika belum ada
                if (!activeSchools.includes(namaSekolah)) {
                    activeSchools.push(namaSekolah);
                }

                // Centang semua checkbox yang terkait dengan sekolah yang dipilih dan tampilkan CCTV-nya
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    const cctvId = checkbox.id.replace('checkbox-', '');
                    toggleCCTV(cctvId, checkbox);
                });

            } else {
                // Sembunyikan CCTV untuk sekolah yang dipilih
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');

                cctvToShow.forEach(cctv => {
                    cctv.style.display = "none";
                });

                // Hapus sekolah dari daftar aktif
                activeSchools = activeSchools.filter(school => school !== namaSekolah);

                // Hapus centang pada semua checkbox yang terkait dengan sekolah yang dipilih dan sembunyikan CCTV-nya
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    const cctvId = checkbox.id.replace('checkbox-', '');
                    toggleCCTV(cctvId, checkbox);
                });
            }

            // Simpan atau hapus daftar sekolah aktif dari localStorage
            if (activeSchools.length === 0) {
                localStorage.removeItem("activeSchools");
            } else {
                localStorage.setItem("activeSchools", JSON.stringify(activeSchools));
            }
        }

        window.onload = function () {
            const stored = localStorage.getItem("activeCCTVs");
            if (stored) {
                const cctvList = JSON.parse(stored);
                cctvList.forEach(id => {
                    const checkbox = document.querySelector(`#checkbox-${id}`);
                    if (checkbox) {
                        checkbox.checked = true;
                        toggleCCTV(id, checkbox);
                    }
                });
            }
        };

        function toggleSidebar() {
            const sidebar = document.querySelector(".side-bar");
            const pageCard = document.querySelector("#pagecard");
            const btnSidebar = document.querySelector(".btn-sidebar"); // Ambil tombol
            const btnMobile = document.querySelector(".btn-mobile"); // Ambil tombol

            sidebar.classList.toggle("active");
            pageCard.classList.toggle("col-md-9");
            pageCard.classList.toggle("col-md-12");

            // Sembunyikan/tampilkan tombol berdasarkan status sidebar
            if (sidebar.classList.contains("active")) {
                btnSidebar.style.display = "none";
                btnMobile.style.display = "none";
            } else {
                btnSidebar.style.display = "block";
                btnMobile.style.display = "block"; // Sesuaikan dengan kebutuhan tampilan mobile
            }
        }

        // Automatically hide sidebar on mobile when clicking outside
        document.addEventListener('click', function (event) {
            const sidebar = document.querySelector('.side-bar');
            const toggleButton = document.querySelector('.btn-sidebar');
            const btnMobile = document.querySelector('.btn-mobile');
            const pageCard = document.querySelector("#pagecard");

            const isClickInside = sidebar.contains(event.target) ||
                toggleButton.contains(event.target) ||
                btnMobile.contains(event.target);

            if (!isClickInside && window.innerWidth <= 767.98) {
                sidebar.classList.remove('active');
                pageCard.classList.add("col-md-12");
                pageCard.classList.remove("col-md-9");
                toggleButton.style.display = "block";
                btnMobile.style.display = "block"; // Pastikan tombol mobile tetap terlihat
            }
        });

        // Mencegah label memicu fungsi toggleCCTV
        document.querySelectorAll('.form-check-label').forEach(label => {
            label.addEventListener('click', function (event) {
                event.preventDefault();
                const checkbox = document.querySelector('#' + label.getAttribute('for'));
                checkbox.checked = !checkbox.checked;

                const id = checkbox.id.replace('checkbox-', '');
                toggleCCTV(id, checkbox);
            });
        });

        // Fungsi untuk menyembunyikan semua CCTV
        function hideAllCCTV() {
            // Hentikan semua streaming dan sembunyikan CCTV
            document.querySelectorAll('.cctv-view').forEach(element => {
                const iframe = element.querySelector('iframe');
                if (iframe) iframe.src = "";
                element.style.display = 'none';
            });

            // Reset UI
            document.querySelectorAll('.icon-toggle.fa-eye-slash').forEach(icon => {
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            });

            // Reset semua checkbox dan hapus statusnya dari localStorage
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
                localStorage.removeItem(checkbox.id); // Hapus status per checkbox
            });

            // Reset dropdown
            const dropdown = document.getElementById("school-dropdown");
            if (dropdown) dropdown.value = "";

            // Hapus SEMUA data terkait CCTV dari localStorage
            localStorage.removeItem("activeSchools");
            localStorage.removeItem("activeCCTVs");
            localStorage.removeItem("selectedSchool");

            // Hapus hash URL
            window.location.hash = "";
        }

        function removeCCTVFromHash(id) {
            const currentHash = window.location.hash.replace('#', '');
            const cctvList = currentHash ? decodeHash(currentHash).split(',') : [];
            const updatedList = cctvList.filter(cctv => cctv !== id);
            window.location.hash = encodeHash(updatedList.join(',')); // Update URL hash dengan hash yang di-encode
        }

        // Ambil pilihan terakhir dari localStorage saat halaman dimuat
        document.addEventListener("DOMContentLoaded", function () {
            loadActiveSchoolsFromLocalStorage();
            updateStatistics();

            // 1. Pulihkan status checkbox dari localStorage
            document.querySelectorAll('input[type="checkbox"][id^="checkbox-"]').forEach(checkbox => {
                const savedState = localStorage.getItem(checkbox.id);
                if (savedState !== null) {
                    checkbox.checked = savedState === 'true';
                    const containerId = checkbox.id.replace('checkbox-', '');
                    toggleCCTV(containerId, checkbox);
                }
            });

            // 2. Ambil daftar CCTV dari hash (prioritas lebih tinggi dari localStorage)
            const activeCCTVs = getActiveCCTVsFromHash();
            activeCCTVs.forEach(id => {
                const checkbox = document.getElementById(`checkbox-${id}`);
                if (checkbox) {
                    checkbox.checked = true;
                    toggleCCTV(id, checkbox);
                }
            });

            // 3. Restore dropdown sekolah
            const selectedSchool = localStorage.getItem("selectedSchool");
            if (selectedSchool) {
                document.getElementById("school-dropdown").value = selectedSchool;
                filterCCTVBySchool(selectedSchool);
            }
        });


        function loadActiveSchoolsFromLocalStorage() {
            const activeSchools = JSON.parse(localStorage.getItem("activeSchools")) || [];
            setTimeout(() => {
                activeSchools.forEach(namaSekolah => {
                    const cctvToShow = document.querySelectorAll(
                        `.cctv-view[data-sekolah="${namaSekolah}"]`);

                    cctvToShow.forEach(cctv => {
                        cctv.style.display = "block";
                        const iframe = cctv.querySelector("iframe");
                        if (iframe) {
                            iframe.src = iframe.getAttribute("data-src");
                        }
                    });

                    const icon = document.querySelector(`.icon-toggle[onclick*="${namaSekolah}"]`);
                    if (icon) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }

                    const checkboxes = document.querySelectorAll(`input[data-sekolah="${namaSekolah}"]`);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                        toggleCCTV(checkbox.id.replace('checkbox-', ''), checkbox);
                    });
                });
            }, 300); // Delay 300ms untuk memastikan semua checkbox udah ada
        }

        // Fungsi untuk menghitung dan menampilkan statistik
        function updateStatistics() {
            // Hitung jumlah CCTV
            const cctvCount = document.querySelectorAll('.cctv-view').length;

            // Hitung jumlah sekolah unik
            const schools = new Set();
            document.querySelectorAll('.cctv-view').forEach(cctv => {
                schools.add(cctv.dataset.sekolah);
            });

            // Hitung jumlah wilayah (daerah)
            const regionCount = document.querySelectorAll('[onclick^="toggleDaerah"]').length;

            // Update tampilan
            document.getElementById('cctvCount').textContent = cctvCount;
            document.getElementById('schoolCount').textContent = schools.size;
            document.getElementById('regionCount').textContent = regionCount;
        }
    </script>
</head>

@extends('layouts.app')
@php
    use App\Models\sekolah;

    $sekolah = sekolah::select('id', 'namaWilayah', 'namaSekolah', 'namaTitik', 'link')->get();
    $groupedCctvs = $sekolah->groupBy('namaWilayah')->map(function ($wilayahGroup) {
        return $wilayahGroup->groupBy('namaSekolah');
    });

    $sekolah = sekolah::select('id', 'namaWilayah', 'namaSekolah', 'namaTitik', 'link')
        ->orderBy('namaWilayah', 'asc')
        ->orderBy('namaSekolah', 'asc')
        ->orderBy('namaTitik', 'asc')
        ->get();

    $groupedCctvs = $sekolah->groupBy('namaWilayah')->map(function ($wilayahGroup) {
        return $wilayahGroup->groupBy('namaSekolah');
    });
@endphp

<body>
    <div class="container-fluid">
        <div class="overlay" onclick="toggleSidebar()"></div>

        <div class="row">
            <div class="card02">
                <button class="btn-mobile" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 style="text-align: center; color: white;">DASHBOARD CCTV SEKOLAH</h1>
            </div>

            <!-- Sidebar (Kiri) -->
            <div id="sidebar" class="col-md-3">
                <div class="side-bar">

                    <button class="btn-sidebar2" onclick="toggleSidebar()"
                        style="position: fixed; top: 10px; left: 10px; z-index: 1001;">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Tombol hide semua cctv yang tampil -->
                    <button id="hide-all-cctv" class="btn-sidebar2" title="Sembunyikan Semua CCTV"
                        onclick="hideAllCCTV()" style="position: fixed; top: 10px; right: 15px; z-index: 1001;">
                        <i class="fas fa-eye-slash"></i>
                    </button>

                    <!-- Menu navigasi -->
                    <div class="menu">
                        @foreach ($groupedCctvs as $wilayah => $sekolahGroup)
                            <div class="item" style="font-size: 12px">
                                <a href="javascript:void(0);" class="sub-btn"
                                    onclick="toggleDaerah('{{ Str::slug($wilayah) }}')">
                                    <i></i> {{ $wilayah }}
                                    <i id="icon-{{ Str::slug($wilayah) }}" class="fas fa-angle-right dropdown"
                                        style="margin-top: 4px;"></i>
                                </a>
                                <div id="{{ Str::slug($wilayah) }}" class="sub-menu">
                                    @foreach ($sekolahGroup as $namaSekolah => $cctvGroup)
                                        <div class="item">
                                            <a href="javascript:void(0);" class="sub-btn"
                                                onclick="toggleDaerah('{{ Str::slug($wilayah) . '-' . Str::slug($namaSekolah) }}')">
                                                <i class="fas fa-eye icon-toggle" style="margin-right: 8px; margin-top: 4px;"
                                                    onclick="event.stopPropagation(); toggleIcon(event, '{{ Str::slug($namaSekolah) }}')"></i>
                                                {{ $namaSekolah }}
                                                <i id="icon-{{ Str::slug($wilayah) . '-' . Str::slug($namaSekolah) }}"
                                                    class="fas fa-angle-right dropdown" style="margin-top: 4px;"></i>
                                            </a>
                                            <div id="{{ Str::slug($wilayah) . '-' . Str::slug($namaSekolah) }}"
                                                class="sub-menu">
                                                @foreach ($cctvGroup as $sekolah)
                                                    <label class="form-check d-flex align-items-center gap-2"
                                                        style="cursor: pointer;">
                                                        <input type="checkbox"
                                                            style="margin-left: -5px; width: 10px; height: 10px; cursor: pointer;"
                                                            id="checkbox-{{ Str::slug($namaSekolah . '-' . $sekolah->namaTitik) }}"
                                                            data-sekolah="{{ Str::slug($namaSekolah) }}"
                                                            onclick="event.stopPropagation(); toggleCCTV('{{ Str::slug($namaSekolah . '-' . $sekolah->namaTitik) }}', this)">
                                                        <span style="font-size: 12px;" class="form-check-label mb-0">
                                                            {{ $sekolah->namaTitik }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div id="pagecard" class="col-md-12">
                <button class="btn-sidebar" onclick="toggleSidebar()" style="top: 47px; left: 35px; z-index: 1001;">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card0" style="margin: 25px 0px 15px 0px;">
                            <h1 style="text-align: center; color: white;">DASHBOARD CCTV SEKOLAH</h1>
                            <h6 style="text-align: center; color: white; font-weight: lighter;">----- Memantau Kondisi
                                Sekolah DIY -----</h6>
                        </div>
                    </div>
                </div>

                <div class="row g-3" style="margin-bottom: 20px;">
                    <div class="col-md-4">
                        <div class="card2 d-flex align-items-center justify-content-center">
                            <p class="fw-bold mb-0">Jumlah CCTV : <span id="cctvCount"></span></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card2 d-flex align-items-center justify-content-center">
                            <p class="fw-bold mb-0">Jumlah Sekolah : <span id="schoolCount"></span></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card2 d-flex align-items-center justify-content-center">
                            <p class="fw-bold mb-0">Jumlah Wilayah : <span id="regionCount"></span></p>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach ($groupedCctvs as $wilayah => $sekolahGroup)
                        @foreach ($sekolahGroup as $namaSekolah => $cctvGroup)
                            @foreach ($cctvGroup as $cctv)
                                @php
                                    // Memeriksa jumlah kata dalam namaTitik
                                    $kata = explode(' ', $cctv->namaTitik);
                                    if (count($kata) > 3) {
                                        // Jika lebih dari 3 kata, singkat dengan mengambil huruf depan setiap kata
                                        $singkatan = implode(
                                            '',
                                            array_map(function ($word) {
                                                return strtoupper(substr($word, 0, 1));
                                            }, $kata),
                                        );
                                    } else {
                                        // Jika 3 kata atau kurang, tampilkan namaTitik secara utuh
                                        $singkatan = $cctv->namaTitik;
                                    }
                                @endphp
                                <div class="col-md-3 col-sm-6 col-xs-12 cctv-view"
                                    id="{{ Str::slug($namaSekolah . '-' . $cctv->namaTitik) }}"
                                    data-sekolah="{{ Str::slug($namaSekolah) }}" style="display: none;">

                                    <div class="card" style="margin-bottom: 5px; padding: 10px; width: 100%; max-height: 285px;">
                                        <a style="font-size: 12pt; font-weight: bold;" class="card-title text-center mb-1">
                                            {{ $cctv->namaSekolah }}
                                        </a>
                                        <a style="font-size: 10pt; margin-top: -4px;" class="card-title text-center mb-3">
                                            {{ $singkatan }}
                                        </a>
                                        <div class="iframe-container" style="margin: -10px 10px 10px 10px;">
                                            <iframe data-src="{{ $cctv->link }}" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
<div class="background-image" style="background-image: url('{{ asset('images/pattern.jpg') }}');"></div>