export async function subscribeUserToPush() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.warn('Push messaging is not supported');
        return false;
    }

    const registration = await navigator.serviceWorker.ready;

    try {
        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(window.vapidPublicKey)
        });

        await sendSubscriptionToServer(subscription);
        return true;
    } catch (e) {
        console.error('Failed to subscribe the user: ', e);
        return false;
    }
}

async function sendSubscriptionToServer(subscription) {
    const key = subscription.getKey('p256dh');
    const token = subscription.getKey('auth');

    // Default to aesgcm if property not supported
    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];

    return await fetch('/api/push/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            endpoint: subscription.endpoint,
            keys: {
                p256dh: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                auth: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null
            },
            encoding: contentEncoding
        })
    });
}

function urlBase64ToUint8Array(base64String) {
    if (!base64String) return null;

    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
