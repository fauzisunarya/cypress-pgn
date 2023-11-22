import { createRoot } from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import { SettingsProvider } from './components/settings';
import { HelmetProvider } from 'react-helmet-async';
import { AuthProvider } from './auth/JwtContext';
import { Provider as ReduxProvider } from 'react-redux';
import { CollapseDrawerProvider } from 'src/contexts/CollapseDrawerContext';
import { store } from 'src/redux/store';
import App from './App';
import './bootstrap';

const root = createRoot(document.getElementById('root') as HTMLElement);
root.render(
    <AuthProvider>
        <ReduxProvider store={store}>
            <CollapseDrawerProvider>
                <HelmetProvider>
                    <SettingsProvider>
                        <BrowserRouter>
                            <App />
                        </BrowserRouter>
                    </SettingsProvider>
                </HelmetProvider>
            </CollapseDrawerProvider>
        </ReduxProvider>
    </AuthProvider>
);
