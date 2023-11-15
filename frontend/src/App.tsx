// routes
import Router from './routes';
// theme
import ThemeProvider from './theme';
// locales
import ThemeLocalization from './locales';
// components
import SnackbarProvider from './Components/snackbar';
import { ThemeSettings } from './Components/settings';
import { MotionLazyContainer } from './Components/animate';

// ----------------------------------------------------------------------

export default function App() {
  
  return (
    <MotionLazyContainer>
      <ThemeProvider>
        <ThemeSettings>
          <ThemeLocalization>
            <SnackbarProvider>
              <Router />
            </SnackbarProvider>
          </ThemeLocalization>
        </ThemeSettings>
      </ThemeProvider>
    </MotionLazyContainer>
  );
}
