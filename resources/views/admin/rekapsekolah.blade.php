@extends('admin.layouts.base')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Rekap Data Sekolah</h4>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm"
                                onclick="openCreateModal()">Tambah</button>

                        </div>

                        <!-- Search Input -->
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3" style="">
                            <!-- Filter Wilayah -->
                            <div style="min-width: 160px; margin-right: 10px;">
                                <select id="filterWilayah" class="form-control form-control-sm" onchange="applyFilters()"
                                    style="height: 2.1rem; border-radius: 0.25rem;">
                                    <option value="">Semua Wilayah</option>
                                    <option value="KAB BANTUL">KAB BANTUL</option>
                                    <option value="KAB SLEMAN">KAB SLEMAN</option>
                                    <option value="KAB GUNUNG KIDUL">KAB GUNUNG KIDUL</option>
                                    <option value="KAB KULON PROGO">KAB KULON PROGO</option>
                                    <option value="KOTA YOGYAKARTA">KOTA YOGYAKARTA</option>
                                </select>
                            </div>

                            <!-- Filter Kategori -->
                            <div style="min-width: 140px; margin-right: 10px;">
                                <select id="filterKategori" class="form-control form-control-sm" onchange="applyFilters()"
                                    style="height: 2.1rem; border-radius: 0.25rem;">
                                    <option value="">Semua Kategori</option>
                                    <option value="SMA">SMA</option>
                                    <option value="SMK">SMK</option>
                                </select>
                            </div>

                            <!-- Search Input -->
                            <div class="flex-grow-1" style="max-width: 850px;">
                                <input type="text" id="searchInput" class="form-control form-control-sm"
                                    placeholder="Cari nama sekolah....." onkeyup="applyFilters()"
                                    style="height: 2.1rem; padding: 5px 10px; border-radius: 0.25rem;">
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table" id="sekolah">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Wilayah</th>
                                        <th class="text-center">Nama Sekolah</th>
                                        <th class="text-center">Lokasi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="sekolahTableBody">

                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination justify-content-center" id="pagination"></ul>
                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formSekolah">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFormLabel">Tambah/Edit CCTV Sekolah</h5>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="id" name="id">
                        <div class="mb-3">
                            <label>Nama Wilayah</label>
                            <select class="form-control" id="wilayah_id" name="wilayah_id" required>
                                <option value="">Pilih Wilayah</option>
                                <option value="1">KAB BANTUL</option>
                                <option value="2">KAB SLEMAN</option>
                                <option value="3">KAB GUNUNG KIDUL</option>
                                <option value="4">KAB KULON PROGO</option>
                                <option value="5">KOTA YOGYAKARTA</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Nama Sekolah</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan nama sekolah..." required>
                        </div>
                        <div class="mb-3">
                            <label>Lokasi</label>

                            <!-- Hidden input untuk menyimpan link lokasi -->
                            <input type="hidden" class="form-control" id="lokasi" name="lokasi" required>


                            <!-- Tampilkan koordinat/link hasil klik -->
                            <p id="lokasi-info" style="margin-top: 5px; color: #555;">
                                Klik pada peta atau gunakan pencarian untuk memilih lokasi.
                            </p>

                            <!-- Peta Leaflet -->
                            <div id="map" style="height: 300px; width: 100%; border: 1px solid #ccc; border-radius: 6px;">
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet & Geocoder -->
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <!-- CSRF Token -->
    <script>
        const csrfToken = "{{ csrf_token() }}";
    </script>

    <!-- Custom JS -->
    <script src="{{ asset('skydash/js/rekap_sekolah.js') }}"></script>

    <script>
        $('#modalForm').on('shown.bs.modal', function () {
            if (map) {
                setTimeout(() => {
                    map.invalidateSize();
                }, 200); // sedikit delay agar modal benar-benar tampil
            }
        });
    </script>
@endpush
