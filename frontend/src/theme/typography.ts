// ----------------------------------------------------------------------

export function remToPx(value: string) {
  return Math.round(parseFloat(value) * 16);
}

export function pxToRem(value: number) {
  return `${value / 16}rem`;
}

export function responsiveFontSizes({ sm, md, lg }: { sm: number; md: number; lg: number }) {
  return {
    '@media (min-width:600px)': {
      fontSize: pxToRem(sm),
    },
    '@media (min-width:900px)': {
      fontSize: pxToRem(md),
    },
    '@media (min-width:1200px)': {
      fontSize: pxToRem(lg),
    },
  };
}

// ----------------------------------------------------------------------

const FONT_PRIMARY = 'Roboto'; // Google Font
// const FONT_SECONDARY = 'CircularStd, sans-serif'; // Local Font

const typography = {
  fontFamily: FONT_PRIMARY,
  fontWeightRegular: 400,
  fontWeightMedium: 600,
  fontWeightBold: 700,
  displayLarge: {
    fontWeight: 500,
    lineHeight: 80 / 64,
    fontSize: pxToRem(57)
  },
  displayMedium: {
    fontWeight: 500,
    lineHeight: 80 / 52,
    fontSize: pxToRem(45)
  },
  displaySmall: {
    fontWeight: 500,
    lineHeight: 80 / 44,
    fontSize: pxToRem(36)
  },
  headlineLarge: {
    fontWeight: 500,
    lineHeight: 80 / 26,
    fontSize: pxToRem(32)
  },
  headlineMedium: {
    fontWeight: 500,
    lineHeight: 80 / 24,
    fontSize: pxToRem(28)
  },
  headlineSmall: {
    fontWeight: 500,
    lineHeight: 80 / 20,
    fontSize: pxToRem(24)
  },
  titleLarge: {
    fontWeight: 500,
    lineHeight: 26/20,
    fontSize: pxToRem(20)
  },
  titleMedium: {
    fontWeight: 500,
    lineHeight: 24/16,
    fontSize: pxToRem(16)
  },
  titleSmall: {
    fontWeight: 500,
    lineHeight: 20/14,
    fontSize: pxToRem(14)
  },
  labelLarge: {
    fontWeight: 500,
    lineHeight: 20/14,
    fontSize: pxToRem(14),
    letterSpacing: 0.1
  },
  labelMedium: {
    fontWeight: 500,
    lineHeight: 16/12,
    fontSize: pxToRem(12),
    letterSpacing: 0.5
  },
  labelSmall: {
    fontWeight: 500,
    lineHeight: 16/11,
    fontSize: pxToRem(11),
    letterSpacing: 0.5
  },
  bodyLarge: {
    fontWeight: 400,
    lineHeight: 24/16,
    fontSize: pxToRem(16),
    letterSpacing: 0.5
  },
  bodyMedium: {
    fontWeight: 400,
    lineHeight: 20/14,
    fontSize: pxToRem(14),
    letterSpacing: 0.25
  },
  bodySmall: {
    fontWeight: 400,
    lineHeight: 16/12,
    fontSize: pxToRem(12),
    letterSpacing: 0.4
  },
  h1: {
    fontWeight: 800,
    lineHeight: 80 / 64,
    fontSize: pxToRem(40),
    ...responsiveFontSizes({ sm: 52, md: 58, lg: 64 }),
  },
  h2: {
    fontWeight: 800,
    lineHeight: 64 / 48,
    fontSize: pxToRem(32),
    ...responsiveFontSizes({ sm: 40, md: 44, lg: 48 }),
  },
  h3: {
    fontWeight: 700,
    lineHeight: 1.5,
    fontSize: pxToRem(24),
    ...responsiveFontSizes({ sm: 26, md: 30, lg: 32 }),
  },
  h4: {
    fontWeight: 700,
    lineHeight: 1.5,
    fontSize: pxToRem(20),
    ...responsiveFontSizes({ sm: 20, md: 24, lg: 24 }),
  },
  h5: {
    fontWeight: 700,
    lineHeight: 1.5,
    fontSize: pxToRem(18),
    ...responsiveFontSizes({ sm: 19, md: 20, lg: 20 }),
  },
  h6: {
    fontWeight: 700,
    lineHeight: 28 / 18,
    fontSize: pxToRem(17),
    ...responsiveFontSizes({ sm: 18, md: 18, lg: 18 }),
  },
  subtitle1: {
    fontWeight: 600,
    lineHeight: 1.5,
    fontSize: pxToRem(16),
  },
  subtitle2: {
    fontWeight: 600,
    lineHeight: 22 / 14,
    fontSize: pxToRem(14),
  },
  body1: {
    lineHeight: 1.5,
    fontSize: pxToRem(16),
  },
  body2: {
    lineHeight: 22 / 14,
    fontSize: pxToRem(14),
  },
  caption: {
    lineHeight: 1.5,
    fontSize: pxToRem(12),
  },
  overline: {
    fontWeight: 700,
    lineHeight: 1.5,
    fontSize: pxToRem(12),
    textTransform: 'uppercase',
  },
  button: {
    fontWeight: 500,
    lineHeight: 24 / 14,
    letterSpacing: 0.25,
    fontSize: pxToRem(14),
    textTransform: 'capitalize',
  },
} as const;

export default typography;
