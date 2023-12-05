import { Outlet } from 'react-router-dom';
// @mui
import { Stack, Container } from '@mui/material';
// hooks
import useOffSetTop from '@/hooks/useOffSetTop';
// config
import { HEADER } from '@/config';
//
import Header from './Header';

// ----------------------------------------------------------------------

export default function CompactLayout({ children } : any) {
  const isOffset = useOffSetTop(HEADER.H_MAIN_DESKTOP);

  return (
    <>
      {/* <Header isOffset={isOffset} /> */}

      <Container component="main" sx={{ mx: 0, px: '16px' }}>
        <Stack
          sx={{
            py: '24px',
            // m: 'auto',
            // maxWidth: 400,
            minHeight: '100vh',
            // textAlign: 'center',
            // justifyContent: 'center',
          }}
        >
          { children }
        </Stack>
      </Container>
    </>
  );
}
