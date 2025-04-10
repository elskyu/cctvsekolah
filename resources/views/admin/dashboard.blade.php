@extends('admin.layouts.base')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome Aamir</h3>
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
                                <h6 class="font-weight-normal">Indonesia</h6>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const apiKey = "{{ config('services.openweather.key') }}";

        const iconMap = {
            '01d': 'mdi-weather-sunny',
            '01n': 'mdi-weather-night',
            '02d': 'mdi-weather-partlycloudy',
            '02n': 'mdi-weather-night',
            '03d': 'mdi-weather-cloudy',
            '03n': 'mdi-weather-cloudy',
            '04d': 'mdi-weather-cloudy',
            '04n': 'mdi-weather-cloudy',
            '09d': 'mdi-weather-pouring',
            '09n': 'mdi-weather-pouring',
            '10d': 'mdi-weather-rainy',
            '10n': 'mdi-weather-rainy',
            '11d': 'mdi-weather-lightning',
            '11n': 'mdi-weather-lightning',
            '13d': 'mdi-weather-snowy',
            '13n': 'mdi-weather-snowy',
            '50d': 'mdi-weather-fog',
            '50n': 'mdi-weather-fog',
        };

        function getWeatherByCoords(lat, lon) {
            fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`)
                .then(response => response.json())
                .then(data => {
                    const temperature = Math.round(data.main.temp);
                    const location = data.name;
                    const iconCode = data.weather[0].icon;
                    const country = data.sys.country;

                    const mdiClass = iconMap[iconCode] || 'mdi-weather-cloudy';

                    const weatherIcon = document.getElementById('weatherIcon');
                    weatherIcon.className = 'mdi mr-2 ' + mdiClass;

                    document.getElementById('tempValue').innerHTML = `${temperature}<sup>°C</sup>`;
                    document.getElementById('location').textContent = location;
                    document.getElementById('country').textContent = country || 'Indonesia';
                })
                .catch(error => {
                    console.error('Error fetching weather data by coordinates:', error);
                });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    getWeatherByCoords(lat, lon);
                },
                (error) => {
                    console.warn('Geolocation gagal, fallback ke Yogyakarta:', error.message);
                    getWeatherByCoords(-7.797068, 110.370529);
                }
            );
        } else {
            console.warn('Browser tidak mendukung geolocation, fallback ke Yogyakarta');
            getWeatherByCoords(-7.797068, 110.370529);
        }
    });
</script>
@endsection