import { Backdrop, Box, Button, CircularProgress, Dialog, DialogActions, DialogContent, DialogTitle, IconButton, Snackbar, styled, Grid } from "@mui/material";
import CloseIcon from '@mui/icons-material/Close';
import BasicDialog from "./BasicDialog";
import { useState } from "react";
import React from "react";

export default function FormDialog({ handleClose, open, maxWidth, isLoading, title, children, handleSubmit, cancelButtonLabel, submitButtonLabel, resetButtonLabel, reset, message, handleDisabled }: any) {
    return (
        <BasicDialog
            handleClose={handleClose}
            open={open}
            maxWidth={maxWidth}
            action={<Action
                isLoading={isLoading}
                handleClose={handleClose}
                cancelButtonLabel={cancelButtonLabel}
                submitButtonLabel={submitButtonLabel}
                resetButtonLabel={resetButtonLabel}
                handleSubmit={handleSubmit}
                reset={reset}
                message={message}
                handleDisabled={handleDisabled}
            />}
            title={title}
        >
            {children}
        </BasicDialog>
    )
}

function Action({ handleClose, message, isLoading, cancelButtonLabel, submitButtonLabel, resetButtonLabel, handleSubmit, reset, handleDisabled }: any) {
    const [openSnackbar, setOpenSnackbar] = useState(false);
    React.useEffect(()=>{
        if(message && message != ''){
            setOpenSnackbar(true)
        }
    },[message])
    return (
        <DialogActions sx={{ backgroundColor: '#fff', p: '8px!important;' }}>
            <Grid container>
                <Grid item xs={6}>
                    { resetButtonLabel &&
                        <Button sx={{ color: '#0088C9' }} onClick={reset} disabled={handleDisabled}>{resetButtonLabel}</Button>
                    }
                </Grid>
                <Grid item xs={6}>
                    <Box display={'flex'} justifyContent={'right'} alignItems={'right'}>
                        <Button onClick={handleClose} sx={{ color: '#0088C9' }}>{cancelButtonLabel}</Button>
                        { submitButtonLabel &&
                            <Button sx={{ color: '#0088C9' }} onClick={handleSubmit} disabled={handleDisabled}>{submitButtonLabel}</Button>
                        }
                    </Box>
                </Grid>
            </Grid>
            <Backdrop
                sx={{ color: '#CED8E0', zIndex: (theme) => theme.zIndex.drawer + 1, backgroundColor: 'rgba(145, 158, 171, 0.48);' }}
                open={isLoading}
            >
                <Box sx={{ backgroundColor: '#fff', borderRadius: '12px', p: 2 }}>
                    <CircularProgress color="inherit" />
                </Box>
            </Backdrop>
            <Snackbar
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'center',
                }}
                open={openSnackbar}
                onClose={() => setOpenSnackbar(false)}
                message={message}
                autoHideDuration={3000}
            />
        </DialogActions>
    )
} 