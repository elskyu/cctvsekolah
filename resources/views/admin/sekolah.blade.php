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
                        <input type="text" id="search" class="form-control mb-3" placeholder="Search" />

                        <div class="table-responsive">
                            <table class="table" id="sekolah">
                                <thead>
                                    <tr>
                                        <th>Nama Wilayah</th>
                                        <th>Nama Sekolah</th>
                                        <th>Titik Wilayah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="sekolahTableBody">
                                    {{-- @foreach ($sekolah as $item)
                                        <tr>
                                            <td>{{ $item->namaWilayah }}</td>
                                            <td>{{ $item->namaSekolah }}</td>
                                            <td>{{ $item->namaTitik }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-sm btn-primary" onclick='openEditModal(@json($item))'>Edit</button>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteSekolah({{ $item->id }})">Delete</button>
                                                </div>                                                
                                            </td>
                                        </tr>
                                    @endforeach --}}
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
                        <h5 class="modal-title" id="modalFormLabel">Tambah/Edit Sekolah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="id" name="id">
                        <div class="mb-3">
                            <label>Nama Wilayah</label>
                            <input type="text" class="form-control" id="namaWilayah" name="namaWilayah" required>
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

    <!-- Pass CSRF ke JS -->
    <script>
        const csrfToken = "{{ csrf_token() }}";
    </script>

    <!-- Custom Script -->
    <script src="{{ asset('skydash/js/sekolah.js') }}"></script>

@endpush
