import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Production (Vercel) uses Pusher Cloud, since Vercel is serverless and cannot host a
// persistent WebSocket server like Reverb. Local dev falls back to self-hosted Reverb
// (same wire protocol, pusher-js talks to both) when no Pusher key is configured.
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;

window.Echo = new Echo(
    pusherKey
        ? {
            broadcaster: 'pusher',
            key: pusherKey,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
            forceTLS: true,
        }
        : {
            broadcaster: 'reverb',
            key: import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: import.meta.env.VITE_REVERB_HOST,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        }
);
