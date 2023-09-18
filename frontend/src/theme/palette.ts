import { alpha } from '@mui/material/styles';

// ----------------------------------------------------------------------

export type ColorSchema = 'primary' | 'secondary' | 'info' | 'success' | 'warning' | 'error';

declare module '@mui/material/styles/createPalette' {
  interface TypeBackground {
    neutral: string;
  }
  interface SimplePaletteColorOptions {
    lighter: string;
    darker: string;
  }
  interface PaletteColor {
    lighter: string;
    darker: string;
  }
}

// SETUP COLORS

const GREY = {
  0: '#FFFFFF',
  100: '#F9FAFB',
  200: '#F4F6F8',
  300: '#DFE3E8',
  400: '#C4CDD5',
  500: '#919EAB',
  600: '#637381',
  700: '#454F5B',
  800: '#212B36',
  900: '#161C24',
};

const NEUTRAL = {
  lighter: '#A3A3A3',
  light: '#606060',
  main: '#333435',
  dark: '#27292B',
  darker: '#0B0C0E',
  contrastText: '#fff',
};

const NEUTRALVAR = {
  lighter: '#BEC0C2',
  light: '#96999C',
  main: '#727476',
  dark: '#484C51',
  darker: '#2E3439',
  contrastText: '#fff',
};

const BLUEGREY = {
  lighter: '#F4F6F8',
  light: '#C4CDD5',
  main: '#919EAB',
  dark: '#637381',
  darker: '#212B36',
  contrastText: '#fff',
}

const PRIMARY = {
  lighter: '#fef7ec',
  light: '#FFBA61',
  main: '#0088C9',
  dark: '#005588',
  darker: '#9C5800',
  contrastText: '#fff',
};

const SECONDARY = {
  lighter: '#FFC6C9',
  light: '#FF727A',
  main: '#D61924',
  dark: '#D10813',
  darker: '#9F0009',
  contrastText: '#fff',
};

const INFO = {
  lighter: '#C3F1FF',
  light: '#67DBFF',
  main: '#11BBF1',
  dark: '#009ACB',
  darker: '#00789D',
  contrastText: '#fff',
};

const SUCCESS = {
  lighter: '#BCFFE7',
  light: '#45F3B5',
  main: '#11D78F',
  dark: '#00BC78',
  darker: '#008555',
  contrastText: GREY[800],
};

const WARNING = {
  lighter: '#FFDDBE',
  light: '#FFB26B',
  main: '#F17D11',
  dark: '#D96800',
  darker: '#B55700',
  contrastText: GREY[800],
};

const ERROR = {
  lighter: '#FFBFCB',
  light: '#FF6C87',
  main: '#F4254A',
  dark: '#D20026',
  darker: '#B80626',
  contrastText: '#fff',
};

const COMMON = {
  common: { black: '#000', white: '#fff' },
  primary: PRIMARY,
  secondary: SECONDARY,
  info: INFO,
  success: SUCCESS,
  warning: WARNING,
  error: ERROR,
  grey: GREY,
  neutral: NEUTRAL,
  neutralvar: NEUTRALVAR,
  blue_grey: BLUEGREY,
  divider: alpha(GREY[500], 0.24),
  action: {
    hover: alpha(GREY[500], 0.08),
    selected: alpha(GREY[500], 0.16),
    disabled: alpha(GREY[500], 0.8),
    disabledBackground: alpha(GREY[500], 0.24),
    focus: alpha(GREY[500], 0.24),
    hoverOpacity: 0.08,
    disabledOpacity: 0.48,
  },
};

export default function palette(themeMode: 'light' | 'dark') {
  const light = {
    ...COMMON,
    mode: 'light',
    text: {
      primary: GREY[800],
      secondary: GREY[600],
      disabled: GREY[500],
    },
    background: { paper: '#f1f5f8', default: '#fff', neutral: GREY[200] },
    action: {
      ...COMMON.action,
      active: GREY[600],
    },
  } as const;

  const dark = {
    ...COMMON,
    mode: 'dark',
    text: {
      primary: '#fff',
      secondary: GREY[500],
      disabled: GREY[600],
    },
    background: {
      paper: GREY[800],
      default: GREY[900],
      neutral: alpha(GREY[500], 0.16),
    },
    action: {
      ...COMMON.action,
      active: GREY[500],
    },
  } as const;

  return themeMode === 'light' ? light : dark;
}
