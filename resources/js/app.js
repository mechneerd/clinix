import './bootstrap';
import Alpine from 'alpinejs';

// ── Alpine ────────────────────────────────────────────────────────────────────
// window.Alpine = Alpine;
// Alpine.start();

// ── Reverb / Echo ─────────────────────────────────────────────────────────────
// Only connects when:
//   1. window.authUserId is set (injected by patient/admin layouts)
//   2. Not on auth / subscription pages
document.addEventListener('livewire:initialized', async () => {
    const userId = window.authUserId;
    if (!userId) return;

    // Pages that must NOT open a socket (no layout injects authUserId there anyway,
    // but this is an extra safety net)
    const skipPaths = ['/login', '/register', '/subscription', '/forgot-password'];
    if (skipPaths.some(p => window.location.pathname.startsWith(p))) return;

    // Lazy-load — keeps bundle small, only downloads pusher-js when actually needed
    const { default: Echo }   = await import('laravel-echo');
    const { default: Pusher } = await import('pusher-js');

    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster      : 'reverb',
        key              : import.meta.env.VITE_REVERB_APP_KEY,
        wsHost           : import.meta.env.VITE_REVERB_HOST,
        wsPort           : import.meta.env.VITE_REVERB_PORT   ?? 80,
        wssPort          : import.meta.env.VITE_REVERB_PORT   ?? 443,
        forceTLS         : (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint     : '/broadcasting/auth',

        // Don't crash the page if Reverb is not running in dev
        disableStats: true,
    });

    // ── Patient private channel ───────────────────────────────────────────────
    Echo.private(`patient.${userId}`)
        .listen('.appointment.booked', (data) => {
            Livewire.dispatch(`echo-private:patient.${userId},appointment.booked`, data);
        })
        .listen('.appointment.status.updated', (data) => {
            Livewire.dispatch(`echo-private:patient.${userId},appointment.status.updated`, data);
        })
        .listen('.lab.report.ready', (data) => {
            Livewire.dispatch(`echo-private:patient.${userId},lab.report.ready`, data);
        });

    // ── Admin / Clinic channel (for live dashboard stats) ────────────────────
    const clinicId = window.authClinicId; // set via <script> in admin layout
    if (clinicId) {
        Echo.private(`clinic.${clinicId}`)
            .listen('.dashboard.stats.updated', (data) => {
                Livewire.dispatch('echo-private:clinic,dashboard.stats.updated', data);
            })
            .listen('.appointment.booked', (data) => {
                Livewire.dispatch('echo-private:clinic,appointment.booked', data);
            });
    }

    console.log('[Reverb] Socket connected — uid:', userId);
});