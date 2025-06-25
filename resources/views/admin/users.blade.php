@extends('admin.layouts.base')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Kelola Pengguna</h4>
                            <!-- Tombol Tambah -->
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm"
                                onclick="openCreateModal()">Tambah</button>
                        </div>

                        <!-- Search Input -->
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="Cari nama, email, atau nomor HP..." onkeyup="searchUsers()">



                        <div class="table-responsive">
                            <table class="table" id="users">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">No Hp</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    {{-- isi Data nya --}}
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
                <form id="formUsers">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFormLabel">Tambah/Edit Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="id" name="id">

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">No HP</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
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
    <script src="{{ asset('skydash/js/users.js') }}"></script>

@endpush