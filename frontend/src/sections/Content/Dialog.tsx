import * as React from 'react';
import { Grid, Typography, Button, Dialog, DialogTitle, DialogContent, DialogActions, Box, Stack, TextField, FormControl, CircularProgress, Backdrop, Snackbar, InputAdornment, InputLabel, Select, MenuItem } from "@mui/material";
import { styled } from '@mui/material/styles';
import CloseIcon from '@mui/icons-material/Close';
import IconButton from '@mui/material/IconButton';
import { useLocales } from 'src/locales';
import FormDialog from 'src/components/dialog/FormDialog';
import { ChangeEvent, ReactNode } from 'react';
import { SelectChangeEvent } from '@mui/material';

import moment from "moment"
import { any } from 'prop-types';
import { useForm, Controller } from "react-hook-form";
import useHelper from "src/hooks/useHelper";
import { api } from 'src/config';

export interface DialogProps {
    openModal: boolean;
    closeModal: ()=>void;
    stateBackdrop: boolean;
    stateSnackbar: boolean;
    data:any;
    dataStatus:any;
    onSubmit:any;
}

export const DialogCreate = (props: DialogProps) => {
    const { translate } = useLocales();
	const [isLoading, setLoading] = React.useState(true);
	const [dataMeter, setDataMeter] = React.useState(null);
	const [dataCustomer, setDataCustomer] = React.useState(null);
	const [dataAddress, setDataAddress] = React.useState(null);
	const [dataContact, setDataContact] = React.useState(null);
	const [dataIdentity, setDataIdentity] = React.useState(null);
    const [dataImage, setImage] = React.useState('');
    const [openBackDrop, setOpenBackDrop] = React.useState(false);
	const [openSnackbar, setOpenSnackbar] = React.useState(false);
    const [message, setMessage] = React.useState('');
    // const { setLoadingShowBackdrop, showSnackbar } = useHelper();

    return (
        <div>

        </div>
    )
}