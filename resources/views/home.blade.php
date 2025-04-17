<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    @vite(['resources/js/app.js'])
    <meta name="user-id" content="{{ auth()->id() }}">
</head>

<body>
    <h2>Welcome, {{ auth()->user()->name }}</h2>
    <p>Status: <span id="status-text">{{ auth()->user()->is_online ? 'Online' : 'Offline' }}</span></p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <script>
        // Make sure Echo is properly set up in app.js and loaded via Vite

        window.Echo.channel('user-status')
            .listen('UserStatusUpdated', (e) => {
                console.log('Event received:', e); // Debug event yang diterima
                if (e.user.id === currentUserId) {
                    document.getElementById('status-text').textContent = e.user.is_online ? 'Online' : 'Offline';
                }
            })
            .error(error => {
                console.error('WebSocket Error:', error); // Menangkap dan mencetak error
            });
    </script>
</body>

</html>
