@extends('admin.layouts.base')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold" id="greetingMessage"><span id="username"></span></h3>
                        <h6 class="font-weight-normal mb-0" id="motivationalMessage"></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card tale-bg">
                    <div class="card-people mt-auto">
                        <img src="{{ asset('skydash/images/dashboard/people.svg') }}" alt="people">
                        <div class="weather-info" id="weatherInfo">
                            <div class="d-flex">
                                <div>
                                    <h2 class="mb-0 font-weight-normal" id="temperature">
                                        <i id="weatherIcon" class="mdi" style="font-size: 30px;"></i>
                                        <span id="tempValue"></span>
                                    </h2>
                                </div>
                                <div class="ml-2">
                                    <h4 class="location font-weight-normal" id="location">Loading...</h4>
                                    <h6 class="font-weight-normal" id="country">Indonesia</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin transparent">
                <div class="row">
                    <!-- Card for Users -->
                    <div class="col-md-6 mb-4 stretch-card transparent">
                        {{-- <a href="{{ route('kelola.users') }}" class="card-link"> --}}
                            <div class="card card-tale">
                                <div class="card-body">
                                    <p class="mb-4">Users</p>
                                    <p class="fs-30 mb-2">{{ $userCount }}</p> <!-- Menampilkan jumlah user -->
                                    <p class="fs-14 text-white">{{ $userMessage }}</p>
                                    <!-- Alternatif untuk menunjukkan perubahan -->
                                </div>
                            </div>
                    </div>

                    <!-- Card for CCTV Panorama -->
                    <div class="col-md-6 mb-4 stretch-card transparent">
                        {{-- <a href="{{ route('panorama.index') }}" class="card-link"> --}}
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4">CCTV Panorama</p>
                                    <p class="fs-30 mb-2">{{ $panoramaCount }}</p> <!-- Menampilkan jumlah CCTV Panorama -->
                                    <p class="fs-14 text-white">{{ $panoramaMessage }}</p>
                                    <!-- Alternatif untuk menunjukkan perubahan -->
                                </div>
                            </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Card for CCTV Sekolah -->
                    <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                        {{-- <a href="{{ route('sekolah.index') }}" class="card-link"> --}}
                            <div class="card card-light-blue">
                                <div class="card-body">
                                    <p class="mb-4">CCTV Sekolah</p>
                                    <p class="fs-30 mb-2">{{ $sekolahCount }}</p> <!-- Menampilkan jumlah CCTV Sekolah -->
                                    <p class="fs-14 text-white">{{ $sekolahMessage }}</p>
                                    <!-- Alternatif untuk menunjukkan perubahan -->
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6 stretch-card transparent">
                        <div class="card card-light-danger">
                            <div class="card-body">
                                <p class="mb-4">Cctv Offline</p>
                                <p class="fs-30 mb-2">{{ $totalOffline }}</p>
                                <p class="fs-14 text-white">{{ $offlineMessage }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Rekap CCTV Offline</h4>

                        <form id="filterForm" class="d-flex flex-wrap align-items-center gap-2 mb-3">

                            <!-- Range -->
                            <div style="margin-right: 10px;">
                                <select id="rangeFilter" class="form-control form-control-sm"
                                    style="height: 2.1rem; min-width: 120px;">
                                    <option value="daily">Harian</option>
                                    <option value="weekly">Mingguan</option>
                                    <option value="monthly">Bulanan</option>
                                </select>
                            </div>

                            <!-- Wilayah -->
                            <div style="margin-right: 10px;">
                                <select id="wilayahFilter" class="form-control form-control-sm"
                                    style="height: 2.1rem; min-width: 160px;">
                                    <option value="">Semua Wilayah</option>
                                    <option value="1">KAB BANTUL</option>
                                    <option value="2">KAB SLEMAN</option>
                                    <option value="3">KAB GUNUNG KIDUL</option>
                                    <option value="4">KAB KULON PROGO</option>
                                    <option value="5">KOTA YOGYAKARTA</option>
                                </select>
                            </div>

                            <!-- Kategori -->
                            <div style="margin-right: 10px;">
                                <select id="kategoriFilter" class="form-control form-control-sm"
                                    style="height: 2.1rem; min-width: 120px;">
                                    <option value="">Semua Kategori</option>
                                    <option value="SMA">SMA</option>
                                    <option value="SMK">SMK</option>
                                </select>
                            </div>

                            <!-- Search -->
                            <div style="max-width: 250px; flex-grow: 1; margin-right: 10px;">
                                <input id="searchInput" type="text" class="form-control form-control-sm"
                                    placeholder="Cari nama sekolah..." style="height: 2.1rem;">
                            </div>

                            <!-- Export -->
                            <div class="ms-auto">
                                <a id="exportBtn" class="btn btn-danger btn-sm"
                                    style="height: 2.1rem; line-height: 1.2rem;">
                                    Export PDF
                                </a>
                            </div>

                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center">Wilayah</th>
                                        <th class="text-center">Sekolah</th>
                                        <th class="text-center">Titik</th>
                                        <th class="text-center">Last Seen</th>
                                        <th class="text-center">Offline Sejak</th>
                                        <th class="text-center">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody id="rekapTableBody">
                                    <!-- Data dari JS akan masuk sini -->
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <ul id="pagination" class="pagination"></ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

    <script>
        const rekapOffline = @json($rekapOffline);
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


    @push('scripts')

        <!-- Script tambahan cuaca -->
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
        <script src="{{ asset('skydash/js/weather.js') }}"></script>

        <!-- Custom Script -->
        <script src="{{ asset('skydash/js/greeting.js') }}"></script>
        <script src="{{ asset('skydash/js/rekap.js') }}"></script>

        <script>
            document.getElementById("exportBtn").addEventListener("click", function () {
                // Ambil nilai dari filter
                const range = document.getElementById("rangeFilter").value;
                const wilayah = document.getElementById("wilayahFilter").value;
                const kategori = document.getElementById("kategoriFilter").value;
                const search = document.getElementById("searchInput").value;

                // Bangun query string
                const params = new URLSearchParams({
                    range: range,
                    wilayah: wilayah,
                    kategori: kategori,
                    search: search,
                });

                // Bangun URL export dan buka di tab baru
                const exportUrl = `/export/cctv-offline?${params.toString()}`;
                window.open(exportUrl, "_blank");
            });
        </script>



    @endpush