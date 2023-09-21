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
            
            {/* <FormDialog
                handleClose={closeDialogCreate}
                open={openCreate}
                cancelButtonLabel={ translate ("DISMISS") }
                submitButtonLabel={ translate ("SAVE CHANGE") }
                handleSubmit={handleSubmit(onSubmitFilter)}
                reset={reset}
                title={ translate("Create Content") }
                maxWidth={'xs'}
            >
                <Box sx={{ width: '100%' }} component="form" noValidate autoComplete="off">
                    <Stack>
                        <TextField fullWidth
                            label={ translate('Put a short note') }
                            type="text"
                        />
                    </Stack>

                    <Stack mt={2}>
                        <TextEditor label={ translate('Address') } control={control} name="address"/>
                    </Stack>

                    <Stack mt={2}>
                        <Controller
                            name="sort"
                            control={control}
                            defaultValue={defaultValues.sort}
                            render={({ field }) => (
                                <FormControl fullWidth>
                                    <InputLabel id="demo-simple-select-label"></InputLabel>
                                    <Select
                                        labelId="demo-simple-select-label"
                                        id="demo-simple-select"
                                        {...field}
                                        onChange={(event: SelectChangeEvent<string>, child: ReactNode) => {
                                            field.onChange(event.target.value)
                                        }}
                                    >
                                        <MenuItem value={'asc'}>{ translate ("Name A - Z") }</MenuItem>
                                        <MenuItem value={'desc'}>{ translate ("Name Z - A") }</MenuItem>
                                    </Select>
                                </FormControl>
                            )}
                        />
                    </Stack>
                </Box>
            </FormDialog> */}
        </div>
    )
}