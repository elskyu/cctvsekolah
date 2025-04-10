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

    <title>Dashboard CCTV</title>
    <script>
        function toggleDaerah(id) {
            const daerahList = document.getElementById(id);
            daerahList.style.display = (daerahList.style.display === "none" || daerahList.style.display === "") ? "block" : "none";
        }

        function toggleCCTV(id, checkbox) {
            const cctvContainer = document.getElementById(id);
            const iframe = cctvContainer.querySelector("iframe");

            if (checkbox.checked) {
                cctvContainer.style.display = "block";
                iframe.src = iframe.getAttribute("data-src");
                addCCTVToHash(id); // Tambahkan CCTV ke URL hash
            } else {
                cctvContainer.style.display = "none";
                iframe.src = "";
                removeCCTVFromHash(id); // Hapus CCTV dari URL hash
            }
        }

        function addCCTVToHash(id) {
            const currentHash = window.location.hash.replace('#', '');
            const cctvList = currentHash ? decodeHash(currentHash).split(',') : [];
            if (!cctvList.includes(id)) {
                cctvList.push(id);
                window.location.hash = encodeHash(cctvList.join(',')); // Update URL hash dengan hash yang di-encode
            }
        }

        function removeCCTVFromHash(id) {
            const currentHash = window.location.hash.replace('#', '');
            const cctvList = currentHash ? decodeHash(currentHash).split(',') : [];
            const updatedList = cctvList.filter(cctv => cctv !== id);
            window.location.hash = encodeHash(updatedList.join(',')); // Update URL hash dengan hash yang di-encode
        }

        function encodeHash(hash) {
            return btoa(hash); // Encode ke Base64
        }

        function decodeHash(encodedHash) {
            return atob(encodedHash); // Decode dari Base64
        }

        window.onload = function () {
            const hash = window.location.hash.replace('#', '');

            if (hash) {
                const cctvList = decodeHash(hash).split(','); // Decode hash dari Base64
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


        document.addEventListener("DOMContentLoaded", function () {
            // Ambil daftar CCTV yang aktif dari URL hash
            const activeCCTVs = getActiveCCTVsFromHash();

            // Loop melalui semua checkbox dan centang yang sesuai
            activeCCTVs.forEach(id => {
                const checkbox = document.getElementById(`checkbox-${id}`);
                if (checkbox) {
                    checkbox.checked = true;
                    toggleCCTV(id, checkbox);
                }
            });
        });

        function getActiveCCTVsFromHash() {
            // Ambil hash dari URL (misalnya: #cctv1,cctv2)
            const hash = window.location.hash.substring(1);
            return hash.split(',').filter(Boolean); // Filter untuk menghapus string kosong
        }

        function addCCTVToHash(id) {
            let activeCCTVs = getActiveCCTVsFromHash();
            if (!activeCCTVs.includes(id)) {
                activeCCTVs.push(id);
                window.location.hash = activeCCTVs.join(',');
            }
        }

        function removeCCTVFromHash(id) {
            let activeCCTVs = getActiveCCTVsFromHash();
            activeCCTVs = activeCCTVs.filter(cctvId => cctvId !== id);
            window.location.hash = activeCCTVs.join(',');
        }

        // Mencegah label memicu fungsi toggleCCTV
        document.querySelectorAll('.form-check-label').forEach(label => {
            label.addEventListener('click', function (event) {
                event.preventDefault();
                const checkbox = document.querySelector('#' + label.getAttribute('for'));
                checkbox.checked = !checkbox.checked;
                toggleCCTV(checkbox.id.replace('checkbox-', ''), checkbox);
            });
        });

        function hideAllCCTV() {
            // Ambil semua elemen CCTV yang sedang ditampilkan
            const cctvElements = document.querySelectorAll('.cctv-view');

            // Loop melalui setiap elemen CCTV dan sembunyikan
            cctvElements.forEach(element => {
                const iframe = element.querySelector("iframe");
                if (iframe) {
                    iframe.src = ""; // Hentikan streaming iframe
                }
                element.style.display = 'none'; // Sembunyikan elemen CCTV
            });

            // Reset semua ikon mata yang aktif
            const activeIcons = document.querySelectorAll('.icon-toggle.fa-eye-slash');
            activeIcons.forEach(icon => {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye'); // Kembalikan ikon ke keadaan mata terbuka
            });

            // Uncheck semua checkbox yang aktif
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    checkbox.checked = false;
                    const id = checkbox.id.replace('checkbox-', '');
                    removeCCTVFromHash(id); // Hapus CCTV dari URL hash
                }
            });

            // Reset dropdown ke default (Pilih Sekolah)
            const dropdown = document.getElementById("school-dropdown");
            if (dropdown) {
                dropdown.value = ""; // Set kembali ke default
            }

            // Hapus state sekolah dari localStorage
            localStorage.removeItem("activeSchools");
            // Update URL hash untuk menghapus semua CCTV
            window.location.hash = "";
        }

        // Ambil pilihan terakhir dari localStorage saat halaman dimuat
        document.addEventListener("DOMContentLoaded", function () {
            const selectedSchool = localStorage.getItem("selectedSchool");
            if (selectedSchool) {
                document.getElementById("school-dropdown").value = selectedSchool;
                filterCCTVBySchool(selectedSchool);
            }
        });

        function toggleIcon(event, namaSekolah) {
            event.stopPropagation();
            event.preventDefault();

            const icon = event.target;
            const cctvToShow = document.querySelectorAll(`.cctv-view[data-sekolah="${namaSekolah}"]`);
            const checkboxes = document.querySelectorAll(`input[data-sekolah="${namaSekolah}"]`); // Pilih semua checkbox

            let activeSchools = JSON.parse(localStorage.getItem("activeSchools")) || [];

            if (icon.classList.contains('fa-eye')) {
                // Tampilkan CCTV
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

                // Centang semua checkbox yang terkait
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    toggleCCTV(checkbox.id.replace('checkbox-', ''), checkbox);
                });

            } else {
                // Sembunyikan CCTV
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');

                cctvToShow.forEach(cctv => {
                    cctv.style.display = "none";
                });

                // Hapus sekolah dari daftar aktif
                activeSchools = activeSchools.filter(school => school !== namaSekolah);

                // Hapus centang checkbox yang sesuai
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    toggleCCTV(checkbox.id.replace('checkbox-', ''), checkbox);
                });
            }

            // Simpan atau hapus daftar sekolah aktif dari localStorage
            if (activeSchools.length === 0) {
                localStorage.removeItem("activeSchools");
            } else {
                localStorage.setItem("activeSchools", JSON.stringify(activeSchools));
            }
        }

        function loadActiveSchoolsFromLocalStorage() {
            const activeSchools = JSON.parse(localStorage.getItem("activeSchools")) || [];

            activeSchools.forEach(namaSekolah => {
                const cctvToShow = document.querySelectorAll(`.cctv-view[data-sekolah="${namaSekolah}"]`);

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

                // Centang checkbox yang sesuai
                const checkbox = document.querySelector(`#checkbox-${namaSekolah}`);
                if (checkbox) {
                    checkbox.checked = true; // Menggunakan properti `checked`
                }
            });
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

        // Panggil fungsi saat halaman dimuat dan setiap ada perubahan
        document.addEventListener("DOMContentLoaded", loadActiveSchoolsFromLocalStorage);
        document.addEventListener("DOMContentLoaded", updateStatistics);
        window.addEventListener('load', updateStatistics);
        window.addEventListener('hashchange', updateStatistics);
        window.addEventListener('load', loadActiveSchoolsFromLocalStorage);
    </script>

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body::-webkit-scrollbar {
            display: none;
            /* Sembunyikan scrollbar di WebKit browsers (Chrome, Safari) */
        }

        .container-fluid {
            width: 100%;
            height: 100vh;
            overflow-y: auto;
        }

        /* Animasi Toggle */
        .wilayah-container {
            display: none;
            padding-left: 10px;
            transition: all 0.3s ease-in-out;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .card-title {
            font-size: 14px;
        }

        .wilayah-name {
            font-size: 14px;
            margin-bottom: 10px;
            display: none;
        }

        .iframe-container {
            position: relative;
            padding-bottom: 57%;
            height: 0;
            overflow: hidden;
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .iframe-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-play {
            fill: #fff;
        }

        .card0 {
            padding: 10px;
            background-color: rgb(152, 177, 124);
            color: white;
            text-align: center;
            border-radius: 5px;
        }

        .card2 {
            padding: 10px;
            background-color: rgb(152, 177, 124);
            color: white;
            text-align: center;
            border-radius: 5px;
            align-items: center;
            justify-content: center;
            max-height: 80px;
        }

        .card02 {
            padding: 10px;
            background-color: rgb(152, 177, 124);
            color: white;
            text-align: center;
            position: fixed;
            z-index: 10;
            left: 0px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .card02 h1 {
            font-size: 14pt;
            font-weight: bold;
            color: #343a40;
        }

        .card0 h1 {
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
        }

        .custom-inline {
            display: inline-block;
        }

        .custom-inline input {
            vertical-align: middle;
            margin-right: 8px;
        }

        .custom-inline label {
            display: inline-block;
            vertical-align: middle;
        }

        /* Sidebar stylings */
        .side-bar {
            background: rgb(55, 55, 55);
            width: 50vh;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            transition: 0.4s ease-in-out;
            transform: translateX(-100%);
            overflow-y: auto;
            padding-top: 20px;
            margin: 0;
            z-index: 1002;
        }

        .side-bar.active {
            transform: translateX(0%);
        }

        .side-bar::-webkit-scrollbar {
            width: 5px;
        }

        .side-bar::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 10px;
        }

        /* Dropdown stylings */
        .menu {
            margin-top: 10px;
            padding: 15px;
        }

        .item {
            margin: 5px 0;
        }

        .menu .item a {
            color: white;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: left;
            padding: 10px 20px;
            background: rgb(13, 124, 102);
            border-radius: 5px;
            transition: 0.3s ease;
        }

        .menu .item a:hover {
            background: rgb(11, 105, 86);
        }

        .sub-menu .item a {
            color: white;
            font-size: 12px;
            text-decoration: none;
            display: flex;
            align-items: left;
            padding: 10px 20px;
            background: rgb(21, 179, 146);
            border-radius: 5px;
            transition: 0.3s ease;
        }

        .sub-menu .item a:hover {
            background: rgb(17, 153, 124);
        }

        /* form Stylings */
        .form-check :hover {
            background: rgb(117, 181, 94);
            margin: 10px;
        }

        .form-check {
            font-size: 16px;
            text-decoration: none;
            display: flex;
            align-items: left;
            padding: 10px 20px;
            background: rgb(136, 214, 108);
            border-radius: 5px;
            transition: 0.3s ease;
            margin: 5px 0px 10px 0px;
            color: black;
        }

        .form-check:hover {
            background: rgb(105, 165, 83);
            color: white;
        }

        .menu .item i {
            margin-right: 15px;
        }

        .sub-btn {
            display: flex;
            justify-content: space-between;
            cursor: pointer;
        }

        .sub-menu {
            color: white;
            display: none;
            padding-left: 20px;
        }

        .sub-menu.show {
            color: white;
            display: block;
        }

        .dropdown {
            transition: transform 0.3s;
        }

        .dropdown.rotate {
            transform: rotate(90deg);
        }

        /* Tombol untuk membuka sidebar */
        .menu-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            font-size: 30px;
            cursor: pointer;
            color: black;
            z-index: 100;
        }

        /* Hide sidebar on mobile by default */
        @media (max-width: 767.98px) {
            .side-bar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background-color: #333;
                /* Warna latar belakang sidebar */
                z-index: 1000;
                transform: translateX(-100%);
                /* Menyembunyikan sidebar dari kiri */
            }

            .side-bar.active {
                transform: translateX(0);
            }
        }

        @media (max-width: 767px) {

            .card0,
            .btn-sidebar {
                display: none !important;
            }

            .card02,
            .btn-mobile {
                display: flex !important;
            }
        }

        /* Untuk tampilan desktop (min 768px) */
        @media (min-width: 768px) {

            .card02,
            .btn-mobile {
                display: none !important;
            }

            .card0,
            .btn-sidebar {
                display: block !important;
            }
        }

        /* Styling untuk tombol sidebar */
        .btn-sidebar {
            position: fixed;
            top: 10px;
            margin-left: 5px;
            z-index: 1001;
            background-color: rgb(152, 177, 124);
            color: white;
            border: none;
            padding: 10px;
            font-size: 28px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn-mobile {
            z-index: 1001;
            background-color: rgb(152, 177, 124);
            margin-top: 4px;
            color: white;
            border: none;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
            left: -100;
        }

        .btn-sidebar:hover {
            color: #343a40;
        }

        .btn-sidebar2 {
            position: fixed;
            top: 10px;
            margin-left: 5px;
            z-index: 1001;
            background-color: rgb(61, 141, 122);
            color: white;
            border: none;
            padding: 10px;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn-sidebar2:hover {
            background-color: rgb(179, 216, 168);
        }

        .btn-dropdown {
            position: fixed;
            top: 10px;
            height: 35px;
            z-index: 1001;
            background-color: rgb(61, 141, 122);
            color: white;
            border: none;
            padding: 10px;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn-dropdown:hover {
            background-color: rgb(179, 216, 168);
            color: #343a40;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
        }

        .overlay.active {
            display: block;
            opacity: 1;
        }

        .background-image {
            position: absolute;
            /* Menghilangkan elemen dari flow dokumen */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: cover;
            background-position: center;
            z-index: -1;
            /* Pastikan gambar berada di belakang konten lain */
        }

        .background-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 110%;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
            transform: rotate(180deg);
        }

        /* icon CheckboX */
        input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 2px;
            display: inline-block;
            position: relative;
            background-color: #fff;
            cursor: pointer;
        }

        input[type="checkbox"]:checked {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        input[type="checkbox"]::before {
            content: "\2713";
            /* Unicode centang */
            font-size: 14px;
            color: white;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }

        input[type="checkbox"]:checked::before {
            display: block;
        }

        .form-check-input {
            appearance: none;
            background-color: red;
        }

        .side-bar.active .btn-sidebar {
            position: absolute;
            left: -999px;
            /* Geser keluar layar */
        }

        .sidebar-active #btn-sidebar {
            display: none;
        }

        .icon-toggle {
            transition: transform 0.2s ease-in-out;
        }

        .icon-toggle:hover {
            transform: scale(1.1);
            /* Efek hover kecil */
        }

        .side-bar.active+.btn-sidebar {
            display: none !important;
        }
    </style>
</head>

@extends('layouts.user_type.auth')
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
                        @foreach($groupedCctvs as $wilayah => $sekolahGroup)
                            <div class="item" style="font-size: 12px">
                                <a href="javascript:void(0);" class="sub-btn"
                                    onclick="toggleDaerah('{{ Str::slug($wilayah) }}')">
                                    <i></i> {{ $wilayah }}
                                    <i class="fas fa-angle-right dropdown" style="margin-top: 4px;"></i>
                                </a>
                                <div id="{{ Str::slug($wilayah) }}" class="sub-menu">
                                    @foreach($sekolahGroup as $namaSekolah => $cctvGroup)
                                        <div class="item">
                                            <a href="javascript:void(0);" class="sub-btn"
                                                onclick="toggleDaerah('{{ Str::slug($wilayah) . '-' . Str::slug($namaSekolah) }}')">
                                                <i class="fas fa-eye icon-toggle" style="margin-right: 8px; margin-top: 4px;"
                                                    onclick="event.stopPropagation(); toggleIcon(event, '{{ Str::slug($namaSekolah) }}')"></i>
                                                {{ $namaSekolah }}
                                                <i class="fas fa-angle-right dropdown"
                                                    style="font-size: 12px; margin-top: 4px;"></i>
                                            </a>
                                            <div id="{{ Str::slug($wilayah) . '-' . Str::slug($namaSekolah) }}"
                                                class="sub-menu">
                                                @foreach($cctvGroup as $sekolah)
                                                    <label class="form-check d-flex align-items-center gap-2"
                                                        style="cursor: pointer;">
                                                        <input
                                                            style="margin-left: -5px; width: 10px; height: 10px; cursor: pointer;"
                                                            type="checkbox" id="checkbox-{{ Str::slug($namaSekolah) }}"
                                                            onclick="event.stopPropagation(); toggleCCTV('{{ Str::slug($sekolah->namaTitik) }}', this)">
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
                    @foreach($groupedCctvs as $wilayah => $sekolahGroup)
                                @foreach($sekolahGroup as $namaSekolah => $cctvGroup)
                                            @foreach($cctvGroup as $cctv)
                                                        @php
                                                            // Memeriksa jumlah kata dalam namaTitik
                                                            $kata = explode(' ', $cctv->namaTitik);
                                                            if (count($kata) > 3) {
                                                                // Jika lebih dari 3 kata, singkat dengan mengambil huruf depan setiap kata
                                                                $singkatan = implode('', array_map(function ($word) {
                                                                    return strtoupper(substr($word, 0, 1));
                                                                }, $kata));
                                                            } else {
                                                                // Jika 3 kata atau kurang, tampilkan namaTitik secara utuh
                                                                $singkatan = $cctv->namaTitik;
                                                            }
                                                        @endphp
                                                        <div class="col-md-3 col-sm-6 col-xs-12 cctv-view" id="{{ Str::slug($cctv->namaTitik) }}"
                                                            data-sekolah="{{ Str::slug($cctv->namaSekolah) }}" style="display: none;">

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