// routes
import Router from 'src/routes';
// theme
import ThemeProvider from 'src/theme';
// locales
import ThemeLocalization from 'src/locales';
// components
import SnackbarProvider from 'src/Components/snackbar';
import { ThemeSettings } from 'src/Components/settings';
import { MotionLazyContainer } from 'src/Components/animate';

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
