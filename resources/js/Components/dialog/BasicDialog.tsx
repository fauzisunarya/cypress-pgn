import { Dialog, DialogContent, DialogTitle, IconButton, Snackbar, styled } from "@mui/material";
import CloseIcon from '@mui/icons-material/Close';

const BootstrapDialog = styled(Dialog)(({ theme }) => ({
    '& .MuiDialogContent-root': {
        padding: theme.spacing(2),
    },
    '& .MuiDialogActions-root': {
        padding: theme.spacing(1),
    },
}));

export interface DialogTitleProps {
    id: string;
    children?: React.ReactNode;
    onClose: () => void;
}

function BootstrapDialogTitle(props: DialogTitleProps) {
    const { children, onClose, ...other } = props;

    return (
        <DialogTitle sx={{ m: 0, p: 2, backgroundColor: '#fff' }} {...other}>
            {children}
            {onClose ? (
                <IconButton
                    aria-label="close"
                    onClick={onClose}
                    sx={{
                        position: 'absolute',
                        right: 8,
                        top: 8,
                        color: (theme) => theme.palette.grey[500],
                    }}
                >
                    <CloseIcon />
                </IconButton>
            ) : null}
        </DialogTitle>
    );
}

const dialogStyle = {
    borderRadius: '8px', // Menyesuaikan nilai sesuai dengan kebutuhan Anda
    backgroundColor: 'transparent',
    boxShadow: 'none',
};


export default function BasicDialog({ handleClose, open, action, children, title, maxWidth } : any) {
    return (
        <BootstrapDialog
            onClose={handleClose}
            aria-labelledby="customized-dialog-title"
            open={open}
            fullWidth 
            maxWidth={maxWidth}
            PaperProps={{ style: dialogStyle }}
        >
            <BootstrapDialogTitle id="customized-dialog-title" onClose={handleClose}>
                {title}
            </BootstrapDialogTitle>
            <DialogContent dividers sx={{ backgroundColor: '#fff' }}>
                {children}
            </DialogContent>
            {
                (action !== undefined) && action            
            }
        </BootstrapDialog>
    )
}
