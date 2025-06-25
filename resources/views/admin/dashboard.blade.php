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
    </div>
@endsection

@push('scripts')
    <!-- Script tambahan cuaca -->
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
    <script src="{{ asset('skydash/js/weather.js') }}"></script>

    <!-- Custom Script -->
    <script src="{{ asset('skydash/js/greeting.js') }}"></script>
@endpush
