@extends('layouts.user_type.auth')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Panorama</title>

    <script>
        // Fungsi Umum
        function toggleDaerah(id) {
            const subMenu = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);

            const isVisible = subMenu.style.display === 'block';

            // Toggle display of submenu
            subMenu.style.display = isVisible ? 'none' : 'block';

            // Ganti ikon class
            if (isVisible) {
                icon.classList.remove('fa-angle-down');
                icon.classList.add('fa-angle-right');
            } else {
                icon.classList.remove('fa-angle-right');
                icon.classList.add('fa-angle-down');
            }
        }

        // Fungsi CCTV
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

            // Update hash URL
            const currentHash = decodeHash(window.location.hash.replace('#', ''));
            let hashArray = currentHash ? currentHash.split(',') : [];

            if (checkbox.checked) {
                if (!hashArray.includes(id)) hashArray.push(id);
            } else {
                hashArray = hashArray.filter(item => item !== id);
            }

            window.location.hash = encodeHash(hashArray.join(','));

            // Simpan ke localStorage
            localStorage.setItem(checkbox.id, checkbox.checked);
        }

        // Fungsi Panorama
        function togglePanorama(containerId, panoramaUrl) {
            const panoramaContainer = document.getElementById(containerId);
            const iframe = panoramaContainer.querySelector("iframe");

            if (panoramaContainer.style.display === "none" || !panoramaContainer.style.display) {
                panoramaContainer.style.display = "block";
                iframe.src = panoramaUrl;
                localStorage.setItem(containerId, 'active');
            } else {
                panoramaContainer.style.display = "none";
                iframe.src = "";
                localStorage.removeItem(containerId);
            }
            updateHash();
        }

        function updateHash() {
            const activeCCTVs = Array.from(document.querySelectorAll('.cctv-view[style*="display: block"]')).map(c => c.id);
            const activePanoramas = Array.from(document.querySelectorAll('.panorama-container[style*="display: block"]')).map(p => p.id);
            const allActive = [...activeCCTVs, ...activePanoramas];
            window.location.hash = encodeHash(allActive.join(','));
        }

        // Fungsi Hash Handling
        function encodeHash(str) {
            return btoa(encodeURIComponent(str)).replace(/\+/g, '-');
        }

        function decodeHash(str) {
            try {
                return decodeURIComponent(atob(str.replace(/-/g, '+')));
            } catch {
                return "";
            }
        }

        // Fungsi Inisialisasi
        function getActiveCCTVsFromHash() {
            const hash = decodeHash(window.location.hash.replace('#', ''));
            return hash.split(',').filter(id => id.startsWith('cctv-'));
        }

        function loadActiveSchoolsFromLocalStorage() {
            const activeSchools = JSON.parse(localStorage.getItem("activeSchools")) || [];
            activeSchools.forEach(namaSekolah => {
                const checkboxes = document.querySelectorAll(`input[data-sekolah="${namaSekolah}"]`);
                checkboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        checkbox.checked = true;
                        toggleCCTV(checkbox.id.replace('checkbox-', ''), checkbox);
                    }
                });
            });
        }

        // Event Handlers
        function handleHashChange() {
            const hash = decodeHash(window.location.hash.replace('#', ''));
            const activeIds = hash.split(',').filter(Boolean);

            // Handle CCTV
            document.querySelectorAll('.cctv-view').forEach(container => {
                const id = container.id;
                const checkbox = document.getElementById(`checkbox-${id}`);
                if (checkbox) {
                    const shouldActivate = activeIds.includes(id);
                    checkbox.checked = shouldActivate;
                    toggleCCTV(id, checkbox);
                }
            });

            // Handle Panorama
            document.querySelectorAll('.panorama-container').forEach(container => {
                const iframe = container.querySelector("iframe");
                if (activeIds.includes(container.id)) {
                    container.style.display = "block";
                    iframe.src = iframe.dataset.src;
                } else {
                    container.style.display = "none";
                    iframe.src = "";
                }
            });
        }

        // DOM Loaded
        document.addEventListener("DOMContentLoaded", function () {
            // Handle Hash pertama kali
            handleHashChange();

            // Handle LocalStorage setelahnya
            setTimeout(() => {
                // Pulihkan status checkbox
                document.querySelectorAll('input[type="checkbox"][id^="checkbox-"]').forEach(checkbox => {
                    const savedState = localStorage.getItem(checkbox.id);
                    if (savedState !== null && !checkbox.checked) {
                        checkbox.checked = savedState === 'true';
                        const containerId = checkbox.id.replace('checkbox-', '');
                        toggleCCTV(containerId, checkbox);
                    }
                });

                // Pulihkan sekolah aktif
                loadActiveSchoolsFromLocalStorage();
            }, 300);

            // Update statistik
            updateStatistics();
        });

        // Fungsi Lainnya (toggleSidebar, updateStatistics, dll tetap sama)
        function toggleSidebar() {
            const sidebar = document.querySelector(".side-bar");
            const pageCard = document.querySelector("#pagecard");
            const btnSidebar = document.querySelector(".btn-sidebar");
            const btnMobile = document.querySelector(".btn-mobile");

            sidebar.classList.toggle("active");
            pageCard.classList.toggle("col-md-9");
            pageCard.classList.toggle("col-md-12");

            if (sidebar.classList.contains("active")) {
                btnSidebar.style.display = "none";
                btnMobile.style.display = "none";
            } else {
                btnSidebar.style.display = "block";
                btnMobile.style.display = "block";
            }
        }

        function updateStatistics() {
            const cctvCount = document.querySelectorAll('.cctv-view').length;
            const regionCount = document.querySelectorAll('[onclick^="toggleDaerah"]').length;
            document.getElementById('cctvCount').textContent = cctvCount;
            document.getElementById('regionCount').textContent = regionCount;
        }

        window.addEventListener('hashchange', handleHashChange);
        window.addEventListener('load', handleHashChange);

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
    </script>
</head>

@extends('layouts.user_type.auth')
@php
    use App\Models\panorama;

    $panorama = panorama::select('id', 'namaWilayah', 'namaTitik', 'link')->get();
    $groupedCctvs = $panorama->groupBy('namaWilayah')->map(function ($wilayahGroup) {
        return $wilayahGroup->groupBy('namaTitik');
    });

    $groupedCctvs = Panorama::select('id', 'namaWilayah', 'namaTitik', 'link')
        ->orderBy('namaWilayah', 'asc')
        ->orderBy('namaTitik', 'asc')
        ->get()
        ->groupBy('namaWilayah'); // Hanya group by wilayah saja
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

                    <div class="menu">
                        @foreach ($groupedCctvs as $wilayah => $panoramaGroup)
                            <div class="item" style="font-size: 12px">
                                <a href="javascript:void(0);" class="sub-btn"
                                    onclick="toggleDaerah('{{ Str::slug($wilayah) }}')">
                                    {{ $wilayah }}
                                    <i id="icon-{{ Str::slug($wilayah) }}" class="fas fa-angle-right dropdown"
                                        style="margin-top: 4px;"></i>
                                </a>
                                <div id="{{ Str::slug($wilayah) }}" class="sub-menu">
                                    @foreach ($panoramaGroup as $panorama)
                                        <label class="form-check d-flex align-items-center gap-2" style="cursor: pointer;">
                                            <input style="margin-left: -5px; width: 10px; height: 10px; cursor: pointer;"
                                                type="checkbox" id="checkbox-{{ Str::slug($panorama->namaTitik) }}"
                                                data-panorama="{{ Str::slug($panorama->namaTitik) }}"
                                                onclick="event.stopPropagation(); toggleCCTV('{{ Str::slug($panorama->namaTitik) }}', this)">
                                            <span style="font-size: 12px;" class="form-check-label mb-0">
                                                {{ $panorama->namaTitik }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div id="pagecard" class="col-md-12">
                <button class="btn-sidebar" onclick="toggleSidebar()" style="top: 47px; left: 35px; z-index: 1001;">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card0" style="margin: 25px 0px 15px 0px;">
                            <h1 style="text-align: center; color: white;">DASHBOARD CCTV PANORAMA</h1>
                            <h6 style="text-align: center; color: white; font-weight: lighter;">----- Memantau Kondisi
                                Panorama DIY -----</h6>
                        </div>
                    </div>
                </div>

                <div class="row g-3" style="margin-bottom: 20px;">
                    <div class="col-md-6">
                        <div class="card2 d-flex align-items-center justify-content-center">
                            <p class="fw-bold mb-0">Jumlah CCTV : <span id="cctvCount"></span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card2 d-flex align-items-center justify-content-center">
                            <p class="fw-bold mb-0">Jumlah Wilayah : <span id="regionCount"></span></p>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach ($groupedCctvs as $wilayah => $panoramaGroup)
                        @foreach ($panoramaGroup as $panorama)
                            <div class="col-md-3 col-sm-6 col-xs-12 cctv-view" id="{{ Str::slug($panorama->namaTitik) }}"
                                data-panorama="{{ Str::slug($panorama->namaTitik) }}" style="display: none;">
                                <div class="card">
                                    <div class="iframe-container" style="margin: -10px 10px 10px 10px;">
                                        <iframe data-src="{{ $panorama->link }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</body>
<div class="background-image" style="background-image: url('{{ asset('images/pattern.jpg') }}');"></div>