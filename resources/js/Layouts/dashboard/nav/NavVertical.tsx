import { useEffect } from 'react';
// import { useLocation } from 'react-router-dom';
// @mui
import { Box, Stack, Drawer } from '@mui/material';
// hooks
import useResponsive from '@/hooks/useResponsive';
import useCollapseDrawer from '@/hooks/useCollapseDrawer';
// config
import { NAV } from '@/config';
// components
import Logo from '@/Components/logo';
import Scrollbar from '@/Components/scrollbar';
import NavSectionVertical from '@/Components/nav-section/vertical';
//
import navConfig from './config';
import NavDocs from './NavDocs';
import NavAccount from './NavAccount';
import NavMain from './NavMain';
import IconButton from '@/Components/icon-button/IconButton';
import ConfigPopover from './ConfigPopover';

// ----------------------------------------------------------------------

type Props = {
  openNav: boolean;
  onCloseNav: VoidFunction;
};

export default function NavVertical({ openNav, onCloseNav }: Props) {
  const pathname  = route().current();

  const isDesktop = useResponsive('up', 'lg');
  const { isCollapse, onToggleCollapse } = useCollapseDrawer();
  useEffect(() => {
    if (openNav) {
      onCloseNav();
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [pathname]);

  const renderContent = (
    <Scrollbar
      sx={{
        height: 1,
        '& .simplebar-content': {
          height: 1,
          display: 'flex',
          flexDirection: 'column',
        },
      }}
    >
      <Stack
        spacing={3}
        gap={2}
        sx={{
          pt: '13px',
          pl: '16px',
          pr: '16px',
          flexShrink: 0,
          // paddingLeft: '13px',
        }}
        direction='row'
        justifyContent='start'
        alignItems='center'
      >
        <IconButton onClick={()=>{onToggleCollapse()}} size='medium' icon='fa6-solid:bars' />
        <Logo type="logo_full" dimension={ { height:40, width: 150 } } />
        <ConfigPopover/>
      </Stack>
        {/* <NavMain/> */}
        {/* <NavAccount /> */}

      <NavSectionVertical data={navConfig} />

      {/* <Box sx={{ flexGrow: 1 }} /> */}

      {/* <NavDocs /> */}
    </Scrollbar>
  );

  return (
    <Box
      component="nav"
      sx={{
        flexShrink: { lg: 0 },
        width: { lg: NAV.W_DASHBOARD },
      }}
    >
      {isDesktop ? (
        <Drawer
          open
          variant="permanent"
          PaperProps={{
            sx: {
              width: NAV.W_DASHBOARD,
              bgcolor: 'transparent',
              borderRightStyle: 'dashed',
            },
          }}
        >
          {renderContent}
        </Drawer>
      ) : (
        <Drawer
          open={openNav}
          onClose={onCloseNav}
          ModalProps={{
            keepMounted: true,
          }}
          PaperProps={{
            sx: {
              width: NAV.W_DASHBOARD,
            },
          }}
        >
          {renderContent}
        </Drawer>
      )}
    </Box>
  );
}
