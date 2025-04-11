document.addEventListener('DOMContentLoaded', function () {
    const metaTag = document.querySelector('meta[name="weather-api-key"]');
    if (!metaTag) {
        console.error("Meta tag for weather API key not found.");
        return;
    }

    const apiKey = metaTag.content;
    // const apiKey = document.querySelector('meta[name="weather-api-key"]').content;

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
                document.getElementById('country').textContent = 'Indonesia';
            })
            .catch(error => {
                console.error('Error fetching weather data by coordinates:', error);
            });
    }

    // if (navigator.geolocation) {
    //     navigator.geolocation.getCurrentPosition(
    //         (position) => {
    //             const lat = position.coords.latitude;
    //             const lon = position.coords.longitude;
    //             getWeatherByCoords(lat, lon);
    //         },
    //         (error) => {
    //             console.warn('Geolocation gagal, fallback ke Yogyakarta:', error.message);
    //             getWeatherByCoords(-7.797068, 110.370529);
    //         }
    //     );
    // } else {
    //     console.warn('Browser tidak mendukung geolocation, fallback ke Yogyakarta');
    //     getWeatherByCoords(-7.797068, 110.370529);
    // }
    // Langsung set ke Jogja
    getWeatherByCoords(-7.797068, 110.370529);
});
