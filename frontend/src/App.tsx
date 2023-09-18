// routes
import Router from './routes';
// theme
import ThemeProvider from 'src/theme';
// locales
import ThemeLocalization from 'src/locales';
// components
import SnackbarProvider from 'src/components/snackbar';
import { MotionLazyContainer } from 'src/components/animate';

// ----------------------------------------------------------------------

export default function App() {
  return (
    <MotionLazyContainer>
      <ThemeProvider>
        {/* <ThemeSettings> */}
          <ThemeLocalization>
            <SnackbarProvider>
              <Router />
            </SnackbarProvider>
          </ThemeLocalization>
        {/* </ThemeSettings> */}
      </ThemeProvider>
    </MotionLazyContainer>
  );
}
