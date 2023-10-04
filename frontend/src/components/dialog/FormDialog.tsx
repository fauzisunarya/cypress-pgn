import { Backdrop, Box, Button, CircularProgress, Dialog, DialogActions, DialogContent, DialogTitle, IconButton, Snackbar, Stack, styled } from "@mui/material";
import BasicDialog from "./BasicDialog";
import { useState } from "react";
import React from "react";

export interface FormDialogProps {
    handleClose: () => void;
    open: boolean;
    maxWidth: number | string;
    isLoading: boolean;
    title: string;
    children?: React.Component;
    handleSubmit ?: () => void | null;
    handleReset ?: () => void | null;
    tertierButtonLabel ?: string | null;
    cancelButtonLabel ?: string | null;
    submitButtonLabel ?: string | null;
}

export default function FormDialog({
    handleClose,
    open,
    maxWidth,
    isLoading,
    title,
    children,
    handleSubmit,
    handleReset,
    tertierButtonLabel,
    cancelButtonLabel,
    submitButtonLabel
}: FormDialogProps | any) {
    return (
        <BasicDialog
            // handleClose={handleClose}
            open={open}
            maxWidth={maxWidth}
            action={<Action
                isLoading={isLoading}
                handleClose={handleClose}
                handleReset={handleReset}
                tertierButtonLabel={tertierButtonLabel}
                cancelButtonLabel={cancelButtonLabel}
                submitButtonLabel={submitButtonLabel}
                handleSubmit={handleSubmit}
            />}
            title={title}
        >
            {children}
        </BasicDialog>
    )
}

function Action({ handleClose,handleReset, message, isLoading, tertierButtonLabel,cancelButtonLabel, submitButtonLabel, handleSubmit }: any) {
    const [openSnackbar, setOpenSnackbar] = useState(false);

    React.useEffect(() => {
        if (message && message != '') {
            setOpenSnackbar(true)
        }
    }, [message])
    return (
        <DialogActions sx={{ backgroundColor: '#fff', p: '8px!important;' }}>
            <Box sx={{ display: 'flex', width: '100%' }}>
                {tertierButtonLabel && <Button onClick={handleReset} color="primary">Reset</Button>}
                <Stack direction={'row'} spacing={1} sx={{ ml: 'auto' }}>
                    {cancelButtonLabel && <Button onClick={handleClose} color="primary">{cancelButtonLabel}</Button> }
                    {submitButtonLabel &&
                        <Button color="primary" onClick={handleSubmit}>{submitButtonLabel}</Button>
                    }
                </Stack>
            </Box>
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