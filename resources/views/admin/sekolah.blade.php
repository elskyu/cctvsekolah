@extends('admin.layouts.base')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">CCTV Sekolah</h4>
                            <!-- Tombol Tambah -->
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm"
                                onclick="openCreateModal()">Tambah</button>
                        </div>

                        <!-- Search Input -->
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="Cari nama sekolah, wilayah, atau titik..." aria-label="Cari CCTV Sekolah"
                            onkeyup="searchcctvsekolah()"
                            style="height: 2.1rem; padding: 5px 10px; border-radius: 0.25rem;">

                        <div class="table-responsive">
                            <table class="table" id="sekolah">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Wilayah</th>
                                        <th class="text-center">Nama Sekolah</th>
                                        <th class="text-center">Titik Wilayah</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="sekolahTableBody">
                                    {{-- Isi data --}}
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                            <input type="text" class="form-control" id="namaSekolah" name="namaSekolah" required>
                        </div>
                        <div class="mb-3">
                            <label>Nama Titik</label>
                            <input type="text" class="form-control" id="namaTitik" name="namaTitik" required>
                        </div>
                        <div class="mb-3">
                            <label>Link CCTV</label>
                            <input type="url" class="form-control" id="link" name="link" required>
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

    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>

    <!-- Pass CSRF ke JS -->
    <script>
        const csrfToken = "{{ csrf_token() }}";
    </script>

    <!-- Custom Script -->
    <script src="{{ asset('skydash/js/sekolah.js') }}"></script>

@endpush