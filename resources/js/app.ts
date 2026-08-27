import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';

createInertiaApp({
    title: (title) => {
        const appName = import.meta.env.VITE_APP_NAME || 'VOLGASHOT';

        if (title?.startsWith('VOLGASHOT - ')) return title;

        return title ? `${title} — ${appName}` : appName;
    },

    progress: {
        color: '#4f39f6',
    },
});
