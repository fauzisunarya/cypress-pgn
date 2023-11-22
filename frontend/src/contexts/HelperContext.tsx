import { Backdrop, Box, Button, CircularProgress, Snackbar, Stack } from '@mui/material';
import { ReactNode, createContext, useState, useEffect } from 'react';
import Iconify from 'src/components/iconify';

export const SNACKBAR_CLOSEABLE = 'closeable';
export const SNACKBAR_NOT_CLOSEABLE = 'not_closeable';
export const SNACKBAR_RESPONSE = 'response';

export type SnackbarOptionsType = {
  type?: string, variant?: string, message: string
}

export type HelperContextProps = {
  isLoadingShowBackdrop: any;
  setLoadingShowBackdrop: (state: boolean) => void;
  showSnackbar: (options: SnackbarOptionsType) => void;
};


const initialState: HelperContextProps = {
  isLoadingShowBackdrop: false,
  setLoadingShowBackdrop: () => { },
  showSnackbar: () => { }
};

const HelperContext = createContext(initialState);

type HelperProviderProps = {
  children: ReactNode;
};

function HelperProvider({ children }: HelperProviderProps) {

  const [load, setLoad] = useState(false);
  const [showSnackbar, setShowSnackbar] = useState(false);
  const [snackbarOption, setSnackbarOptions] = useState<SnackbarOptionsType>();

  const handleShowSnackbar = (options: SnackbarOptionsType) => {
    setShowSnackbar(true);
    setSnackbarOptions(options);
  }

  return (
    <HelperContext.Provider
      value={{
        isLoadingShowBackdrop: load,
        setLoadingShowBackdrop: setLoad,
        showSnackbar: handleShowSnackbar
      }}
    >
      {children}
      <Backdrop
        sx={{ color: '#CED8E0', zIndex: (theme) => theme.zIndex.drawer + 999, backgroundColor: 'rgba(145, 158, 171, 0.48);' }}
        open={load}
      >
        <Box sx={{ backgroundColor: '#fff', borderRadius: '12px', p: 2 }}>
          <CircularProgress color="inherit" />
        </Box>
      </Backdrop>
      <SnackbarType option={snackbarOption} showSnackbar={showSnackbar} handleClose={() => setShowSnackbar(false)} />
    </HelperContext.Provider>
  );
}

const actionNotCloseable = (
  <Iconify icon="fa:refresh" />
);

const actionResponse = (
  <Stack direction="column">
    <Button>Yes</Button>
    <Button>No</Button>
  </Stack>
);

const SnackbarType = ({ option, showSnackbar, handleClose }: {
  option: SnackbarOptionsType | undefined;
  showSnackbar: boolean;
  handleClose: () => void;
}) => {
  if (option?.type == SNACKBAR_NOT_CLOSEABLE) {
    return (
      <Snackbar
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'center',
        }}
        open={showSnackbar}
        message={option.message}
        action={actionNotCloseable}
      />
    )
  }

  if (option?.type == SNACKBAR_RESPONSE) {
    return (
      <Snackbar
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'center',
        }}
        open={showSnackbar}
        message={option.message}
        action={actionResponse}
      />
    )
  }

  return (
    <Snackbar
      anchorOrigin={{
        vertical: 'bottom',
        horizontal: 'center',
      }}
      open={showSnackbar}
      onClose={handleClose}
      message={option?.message}
      autoHideDuration={5000}
    />
  )
}

export { HelperProvider, HelperContext };
