@extends('admin.layouts.base')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome <span id="username"></span></h3>
                    <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have
                        <span class="text-primary">3 unread alerts!</span>
                    </h6>
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
                <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-tale">
                        <div class="card-body">
                            <p class="mb-4">Today’s Bookings</p>
                            <p class="fs-30 mb-2">4006</p>
                            <p>10.00% (30 days)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-dark-blue">
                        <div class="card-body">
                            <p class="mb-4">Total Bookings</p>
                            <p class="fs-30 mb-2">61344</p>
                            <p>22.00% (30 days)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                    <div class="card card-light-blue">
                        <div class="card-body">
                            <p class="mb-4">Number of Meetings</p>
                            <p class="fs-30 mb-2">34040</p>
                            <p>2.00% (30 days)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                    <div class="card card-light-danger">
                        <div class="card-body">
                            <p class="mb-4">Number of Clients</p>
                            <p class="fs-30 mb-2">47033</p>
                            <p>0.22% (30 days)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const user = JSON.parse(localStorage.getItem('user'));
        if (user && user.name) {
            document.getElementById('username').textContent = user.name;
        } else {
            document.getElementById('username').textContent = 'Guest';
        }
    });
</script>
<!-- Script tambahan cuaca -->
<script src="{{ asset('skydash/js/weather.js') }}"></script>
@endpush

