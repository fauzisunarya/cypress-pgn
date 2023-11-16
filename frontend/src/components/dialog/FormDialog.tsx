import { Backdrop, Box, Button, CircularProgress, Dialog, DialogActions, DialogContent, DialogTitle, IconButton, Snackbar, styled, Grid } from "@mui/material";
import CloseIcon from '@mui/icons-material/Close';
import BasicDialog from "./BasicDialog";
import { useState } from "react";
import React from "react";

export default function FormDialog({ handleClose, open, maxWidth, isLoading, title, children, handleSubmit, handleReject, cancelButtonLabel, rejectButtonLabel, submitButtonLabel, resetButtonLabel, reset, message, handleDisabled }: any) {
    return (
        <BasicDialog
            handleClose={handleClose}
            open={open}
            maxWidth={maxWidth}
            action={<Action
                isLoading={isLoading}
                handleClose={handleClose}
                cancelButtonLabel={cancelButtonLabel}
                rejectButtonLabel={rejectButtonLabel}
                submitButtonLabel={submitButtonLabel}
                resetButtonLabel={resetButtonLabel}
                handleSubmit={handleSubmit}
                handleReject={handleReject}
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

function Action({ handleClose, message, isLoading, cancelButtonLabel, rejectButtonLabel, submitButtonLabel, resetButtonLabel, handleSubmit,handleReject, reset, handleDisabled }: any) {
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
                        <Button onClick={reset} disabled={handleDisabled}>{resetButtonLabel}</Button>
                    }
                </Grid>
                <Grid item xs={6}>
                    <Box display={'flex'} justifyContent={'right'} alignItems={'right'}>
                        { cancelButtonLabel &&
                            <Button onClick={handleClose}>{cancelButtonLabel}</Button>
                        }

                        { rejectButtonLabel &&
                            <Button onClick={handleReject}>{rejectButtonLabel}</Button>
                        }
                        
                        { submitButtonLabel &&
                            <Button onClick={handleSubmit} disabled={handleDisabled}>{submitButtonLabel}</Button>
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