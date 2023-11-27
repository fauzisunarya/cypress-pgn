import { alpha, Theme } from '@mui/material/styles';
import { ButtonProps, IconButtonProps } from '@mui/material';

// ----------------------------------------------------------------------

const COLORS = ['primary', 'secondary', 'info', 'success', 'warning', 'error'] as const;

// NEW VARIANT
declare module '@mui/material/IconButton' {
  interface IconButtonPropsVariantOverrides {
    soft: true;
    thick: true;
  }
}

type ModifiedType = Omit<IconButtonProps, 'variant'> & {
  variant: string
}

export default function IconButton(theme: Theme) {
  const isLight = theme.palette.mode === 'light';

  const rootStyle = (ownerState: ModifiedType) => {
    const inheritColor = ownerState.color === 'inherit';
    const softVariant = ownerState.variant === 'soft';
    const thickVariant = ownerState.variant === 'thick';

    const smallSize = ownerState.size === 'small';

    const mediumSize = ownerState.size === 'medium';

    const largeSize = ownerState.size === 'large';

    const defaultStyle = {
      ...(!ownerState.variant && {
        ...(softVariant && {
          backgroundColor: theme.palette.grey[100]
        }),
        ...(thickVariant && {
          backgroundColor: theme.palette.grey[300]
        })
      })
    };

    const colorStyle = COLORS.map((color) => ({
      ...(ownerState.color === color && {
        ...(softVariant && {
          backgroundColor: theme.palette[color].lighter
        }),
        ...(thickVariant && {
          backgroundColor: theme.palette[color].main,
          color:'white',
        }),
      }),
    }));

    const disabledState = {};

    const size = {
      ...(smallSize && {
        height: 28,
        width: 28,
      }),
      ...(mediumSize && {
        height: 40,
        width: 40,
      }),
      ...(largeSize && {
        height: 48,
        width: 48,
      }),
    };

    return [...colorStyle, defaultStyle, disabledState, size];
  };

  return {
    MuiIconButton: {
      defaultProps: {
        disableElevation: true,
      },

      styleOverrides: {
        root: ({ ownerState }: { ownerState: ModifiedType }) => rootStyle(ownerState),
      },
    },
  };
}
