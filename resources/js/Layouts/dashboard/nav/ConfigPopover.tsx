import { useState, useEffect, useCallback, useMemo } from 'react';
// import { useNavigate } from 'react-router-dom';
// @mui
import { alpha } from '@mui/material/styles';
import { Box, Divider, Typography, Stack, MenuItem, Avatar, Button, Link, IconButton, ButtonGroup } from '@mui/material';
import Iconify from '@/Components/iconify';
// routes
// import { PATH_AUTH } from '../../../routes/paths';
// auth
import { useAuthContext } from '../../../auth/useAuthContext';
// components
import { CustomAvatar } from '@/Components/custom-avatar';
import { useSnackbar } from '@/Components/snackbar';
import MenuPopover from '@/Components/menu-popover';
import { IconButtonAnimate } from '@/Components/animate';
// import PersonIcon from '@mui/icons-material/Person';
import useLocales from '@/locales/useLocales';

// ----------------------------------------------------------------------

const OPTIONS = [
  // {
  //   label: 'Home',
  //   linkTo: '/',
  // },
  {
    label: 'Profile',
    linkTo: '/profile',
  },
  // {
  //   label: 'Settings',
  //   linkTo: '/',
  // },
];

// ----------------------------------------------------------------------

export default function ConfigPopover() {
  const [openPopover, setOpenPopover] = useState<HTMLElement | null>(null);

  const handleOpenPopover = (event: React.MouseEvent<HTMLElement>) => {
    setOpenPopover(event.currentTarget);
  };

  const handleClosePopover = () => {
    setOpenPopover(null);
  };

  return (
    <>
      <IconButton
        onClick={handleOpenPopover}
        sx={{
          p: 0,
          ...(openPopover && {
            '&:before': {
              zIndex: 1,
              content: "''",
              width: '100%',
              height: '100%',
              border: '1px solid #DADDE1',
              borderRadius: '50%',
              position: 'absolute',
              // bgcolor: (theme) => alpha(theme.palette.grey[900], 0.8),
            },
          }),
        }}
      >
        <Iconify icon="fa6-solid:ellipsis-vertical" />
      </IconButton>

      <MenuPopover open={openPopover} onClose={handleClosePopover} sx={{ width: 270, ml: 2, p: 2, boxShadow: '-40px 40px 40px -8px rgba(145, 158, 171, 0.24);' }}>

        <Stack>
          <LanguageMenu />
          {/* <MenuItem>
          </MenuItem> */}

          {/* <MenuItem onClick={handleLogout}>
            Logout
          </MenuItem> */}
        </Stack>
      </MenuPopover>
    </>
  );
}


function LanguageMenu() {
  const { allLangs, currentLang, onChangeLang } = useLocales();
  const handleChangeLang = (newLang: string) => {
    onChangeLang(newLang);
  };
  return (
    <>
      <ButtonGroup variant="outlined" aria-label="outlined button group" >
        {allLangs.map((option: any, index: number) => (
          <Button
          sx={((theme) => ({
            width:'100%',
            borderRadius: 20,
            borderColor: theme.palette.divider,
            color: (currentLang.value != option.value) ? theme.palette.common.black : theme.palette.primary.main,
            backgroundColor: (currentLang.value == option.value) ? theme.palette.primary.lighter : ''
          }))}
          key={index} 
          onClick={() => handleChangeLang(option.value)} >
            {(currentLang.value == option.value) ? <Iconify icon="fa6-solid:check" sx={{ mr: 1 }} /> : null}
            {option.label}
          </Button>
        ))}
      </ButtonGroup>
    </>
  )
}

