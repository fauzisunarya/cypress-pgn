import './bootstrap';
import '../css/app.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { SettingsProvider } from '@/Components/settings';
import { HelmetProvider } from 'react-helmet-async';
import ThemeProvider from '@/theme';
import { AuthProvider } from './auth/JwtContext';
import AuthGuard from '@/auth/AuthGuard';
import { HelperProvider } from './contexts/HelperContext';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <AuthProvider>
                <HelmetProvider>
                    <ThemeProvider>
                        <SettingsProvider>
                            <HelperProvider>    
                                <AuthGuard>
                                    <App {...props} />
                                </AuthGuard>
                            </HelperProvider>
                        </SettingsProvider>
                    </ThemeProvider>
                </HelmetProvider>
            </AuthProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});
