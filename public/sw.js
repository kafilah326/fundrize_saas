self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const data = event.data ? event.data.json() : {};

    const title = data.title || 'Notification';
    const message = data.message || '';
    const icon = '/icons/icon-192.png';
    const badge = '/icons/icon-192.png';
    const url = data.data ? data.data.url : '/admin/dashboard';

    const options = {
        body: message,
        icon: icon,
        badge: badge,
        vibrate: [100, 50, 100],
        data: {
            url: url
        },
        // Actions optional
        actions: [
            {
                action: 'open',
                title: 'Buka',
                icon: '/icons/icon-192.png'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    // Check if open windows exist
    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(function (windowClients) {
            // If exists, focus it
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url === event.notification.data.url && 'focus' in client) {
                    return client.focus();
                }
            }
            // If not, open new window
            if (clients.openWindow) {
                return clients.openWindow(event.notification.data.url);
            }
        })
    );
});
