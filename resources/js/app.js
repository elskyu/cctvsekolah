import './bootstrap';
import './echo';

const currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');

window.Echo.channel('user-status')
    .listen('.UserStatusUpdated', (e) => {
        console.log('Received:', e);
        if (e.user.id == currentUserId) {
            document.getElementById('status-text').textContent = e.user.is_online ? 'Online' : 'Offline';
        }
    })
    .error(err => {
        console.error('WebSocket error:', err);
    });
