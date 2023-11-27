import { forwardRef } from 'react';
import { Link as RouterLink } from '@inertiajs/react';
// @mui
import { useTheme } from '@mui/material/styles';
import { Box, Link, BoxProps } from '@mui/material';
import { personalization } from '../../config';

// ----------------------------------------------------------------------


export interface LogoProps extends BoxProps {
  disabledLink?: boolean;
  dimension ?: {
    width ?: number | string;
    height ?: number | string;
  },
  type ?: any
}

const Logo = forwardRef<HTMLDivElement, LogoProps>(
  ({ disabledLink = false, sx,type = 'logo', dimension = { height: 40 } , ...other }, ref) => {
    let logo_type;
    if(type == 'logo_full') logo_type = personalization.logo_full;
    if(type == 'logo') logo_type = personalization.logo;
    if(type == 'logo_light') logo_type = personalization.logo_light;
    const logo = (
      <Box
        component="img"
        src={ logo_type }
        sx={{ ...dimension, cursor: 'pointer', ...sx }}
      />
    );

    if (disabledLink) {
      return <>{logo}</>;
    }

    return (
      <RouterLink href="/" style={{ display: 'contents' }}>
        {logo}
      </RouterLink>
    );
  }
);


export default Logo;
