// Fungsi untuk menampilkan ucapan sesuai dengan waktu dan pesan motivasi
function updateGreetingAndMotivation() {
    const greetingMessageElement = document.getElementById("greetingMessage");
    const motivationalMessageElement = document.getElementById("motivationalMessage");
    const usernameElement = document.getElementById("username");

    // Mendapatkan username dari cookie
    const user = Cookies.get('user');
    let username = 'Admin'; // Default jika tidak ada user di cookie

    // Jika ada user di cookie, ambil nama pengguna
    if (user) {
        const parsedUser = JSON.parse(user);
        if (parsedUser.name) {
            username = parsedUser.name;
        }
    }

    // Mendapatkan jam saat ini
    const currentHour = new Date().getHours();

    let greetingMessage;
    let motivationalMessage;

    // Logika untuk menentukan ucapan dan pesan motivasi
    if (currentHour >= 6 && currentHour < 11) {
        greetingMessage = "Selamat Pagi";
        motivationalMessage = "Awali hari dengan secangkir kopi dan senyuman!";
    } else if (currentHour >= 11 && currentHour < 15) {
        greetingMessage = "Selamat Siang";
        motivationalMessage = "Makan siang yang enak, temani dengan semangat yang tinggi!";
    } else if (currentHour >= 15 && currentHour < 18) {
        greetingMessage = "Selamat Sore";
        motivationalMessage = "Sore yang tenang, mari siapkan diri untuk malam yang produktif!";
    } else {
        greetingMessage = "Selamat Malam";
        motivationalMessage = "Malam ini, waktunya istirahat. Besok akan jadi hari yang lebih baik!";
    }

    // Menampilkan ucapan dan username
    greetingMessageElement.innerHTML = `${greetingMessage}, ${username}`;
    // Menampilkan pesan motivasi
    motivationalMessageElement.innerHTML = motivationalMessage;
}

// Menjalankan fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    updateGreetingAndMotivation(); // Panggil updateGreetingAndMotivation ketika halaman dimuat
});
