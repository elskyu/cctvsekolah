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
    <script src="{{ asset('js/sekolah.js') }}"></script>
    <script>
        window.onload = function () {
            fetch('/api/jumlah-wilayah')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('jumlahWilayah').textContent = data.jumlah;
                })
                .catch(err => console.error(err));
        };
    </script>
</head>

@extends('layouts.user_type.auth')
@php
    use App\Models\panorama;

    $wilayahMapping = [
        1 => "KAB BANTUL",
        2 => "KAB SLEMAN",
        3 => "KAB GUNUNG KIDUL",
        4 => "KAB KULON PROGO",
        5 => "KOTA YOGYAKARTA",
    ];

    $panorama = panorama::select('id', 'wilayah_id', 'namaTitik', 'link')->get();
    $groupedCctvs = $panorama->groupBy('wilayah_id')->map(function ($wilayahGroup) {
        return $wilayahGroup->groupBy('namaTitik');
    });

    $groupedCctvs = Panorama::select('id', 'wilayah_id', 'namaTitik', 'link')
        ->orderBy('wilayah_id', 'asc')
        ->orderBy('namaTitik', 'asc')
        ->get()
        ->groupBy('wilayah_id'); // Hanya group by wilayah saja
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
                                    onclick="toggleDaerah('{{ Str::slug($wilayahMapping[$wilayah] ?? $wilayah) }}')">
                                    {{ $wilayahMapping[$wilayah] ?? 'WILAYAH TIDAK DIKETAHUI' }}
                                    <i id="icon-{{ Str::slug($wilayahMapping[$wilayah] ?? $wilayah) }}"
                                        class="fas fa-angle-right dropdown" style="margin-top: 4px;"></i>
                                </a>
                                <div id="{{ Str::slug($wilayahMapping[$wilayah] ?? $wilayah) }}" class="sub-menu">
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
                            <p class="fw-bold mb-0">Jumlah Wilayah : <span id="jumlahWilayah"></span></p>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach ($groupedCctvs as $wilayah => $panoramaGroup)
                        @foreach ($panoramaGroup as $panorama)
                            @php
                                // Memeriksa jumlah kata dalam namaTitik
                                $kata = explode(' ', $panorama->namaTitik);
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
                                    $singkatan = $panorama->namaTitik;
                                }
                            @endphp
                            <div class="col-md-3 col-sm-6 col-xs-12 cctv-view" id="{{ Str::slug($panorama->namaTitik) }}"
                                data-panorama="{{ Str::slug($panorama->namaTitik) }}" style="display: none;">
                                <div class="card">
                                    <a style="font-size: 12pt; font-weight: bold;" class="card-title text-center mb-1">
                                        {{ $wilayahMapping[$panorama->wilayah_id] ?? 'Wilayah Tidak Diketahui' }}
                                    </a>
                                    <a style="font-size: 10pt; margin-top: -4px;" class="card-title text-center mb-3">
                                        {{ $singkatan }}
                                    </a>
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