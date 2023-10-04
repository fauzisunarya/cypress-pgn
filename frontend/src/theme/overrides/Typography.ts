import { Theme } from '@mui/material/styles';

// ----------------------------------------------------------------------

declare module '@mui/material/Typography' {
  interface TypographyPropsVariantOverrides {
    displayLarge: true;
    displayMedium: true;
    displaySmall: true;
    headlineLarge: true;
    headlineMedium: true;
    headlineSmall: true;
    titleLarge: true;
    titleMedium: true;
    titleSmall: true;
    labelLarge: true;
    labelMedium: true;
    labelSmall: true;
    bodyLarge: true;
    bodyMedium: true;
    bodySmall: true;
  }
}

export default function Typography(theme: Theme) {
  
  return {
    MuiTypography: {
      styleOverrides: {
        paragraph: {
          marginBottom: theme.spacing(2)
        },
        gutterBottom: {
          marginBottom: theme.spacing(1)
        }
      },
      variants: [
        {
          props: { variant: 'displayLarge' }
        },
        {
          props: { variant: 'displayMedium' }
        },
        {
          props: { variant: 'displaySmall' }
        },
        {
          props: { variant: 'headlineLarge' }
        },
        {
          props: { variant: 'headlineMedium' }
        },
        {
          props: { variant: 'headlineSmall' }
        },
        {
          props: { variant: 'titleLarge' }
        },
        {
          props: { variant: 'titleMedium' }
        },
        {
          props: { variant: 'titleSmall' }
        },
        {
          props: { variant: 'labelLarge' }
        },
        {
          props: { variant: 'labelMedium' }
        },
        {
          props: { variant: 'labelSmall' }
        },
        {
          props: { variant: 'bodyLarge' }
        },
        {
          props: { variant: 'bodyMedium' }
        },
        {
          props: { variant: 'bodySmall' }
        },
      ],
    }
  };
}
