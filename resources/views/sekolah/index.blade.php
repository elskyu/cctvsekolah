@extends('layouts.user_type.guest')

<script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</script>

<head>
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

        .floating-button {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 1000;
            /* Warna tombol */
            padding: 12px 20px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .container-fluid {
            width: 100%;
            height: 100vh;
            overflow-y: auto;
            scrollbar-width: none;
        }


        .floating-button:hover {
            background-color: rgba(77, 255, 0, 0.63);
            /* Warna saat hover */
            transform: scale(1.05);
            color: white;
        }

        button {
            height: 35px;
            /* Samakan tinggi semua tombol */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            border-radius: 5px;
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
    </style>
</head>

<body>
    @section('content')
        <div class="pb-0 d-flex justify-content-end">
            <a href="{{ route('sekolah.create') }}" class="floating-button btn"
                style="background-color: rgba(255, 0, 183, 0.63); color: white;">
                Tambah CCTV
            </a>
        </div>
        <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6 class="text-center">CCTV SEKOLAH</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nama Wilayah</th>
                                                <th class="text-center">Nama Sekolah</th>
                                                <th class="text-center">Titik Wilayah</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sekolah as $item)
                                                <tr>
                                                    <td class="text-center align-middle">{{ $item->namaWilayah }}</td>
                                                    <td class="text-center align-middle">{{ $item->namaSekolah }}</td>
                                                    <td class="text-center align-middle">{{ $item->namaTitik }}</td>
                                                    <td class="text-center align-middle">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <!-- Form Edit -->
                                                            <form action="{{ route('sekolah.edit', $item->id) }}" method="GET">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-primary">Edit</button>
                                                            </form>

                                                            <!-- Form Delete -->
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
        </main>
    @endsection
    <div class="background-image" style="background-image: url('{{ asset('images/pattern.jpg') }}');"></div>
</body>