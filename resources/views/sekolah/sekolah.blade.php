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
    <script src="{{ asset('js/sekolah.js') }}"></script>
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
                                                        <input
                                                            style="margin-left: -5px; width: 10px; height: 10px; cursor: pointer;"
                                                            type="checkbox" id="checkbox-{{ Str::slug($sekolah->namaTitik) }}"
                                                            data-sekolah="{{ Str::slug($namaSekolah) }}"
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