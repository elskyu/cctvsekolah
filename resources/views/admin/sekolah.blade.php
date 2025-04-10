@extends('admin.layouts.base')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">CCTV Sekolah</h4>
                            <a href="{{ route('sekolah.create') }}" class="btn btn-success btn-sm">+ Tambah</a>
                        </div>                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Wilayah</th>
                                        <th>Nama Sekolah</th>
                                        <th>Titik Wilayah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sekolah as $item)
                                            <tr>
                                                <td >{{ $item->namaWilayah }}</td>
                                                <td >{{ $item->namaSekolah }}</td>
                                                <td >{{ $item->namaTitik }}</td>
                                                <td >
                                                    <div class="d-flex">
                                                        <!-- Form Edit -->
                                                        <form action="{{ route('sekolah.edit', $item->id) }}" method="GET">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary">Edit</button>
                                                        </form>
                                                            <!- Form Delete -->
                                                            <form action="{{ route('sekolah.delete', $item->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus CCTV ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
