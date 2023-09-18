import Page from 'src/components/Page';
import { useEffect, useState } from "react";
import { Box, Grid, Typography, MenuItem, Link, Stack, Button, FormControl, InputLabel, Select, Backdrop, Snackbar, CircularProgress } from "@mui/material"
import { personalization } from 'src/config';
import { SelectChangeEvent } from '@mui/material';
import { styled } from '@mui/material/styles';
import { useLocales } from 'src/locales';
import Datatable from 'src/components/datatables/Datatable';
import Chip from 'src/components/chip/Chip';
import { GridToolbar } from '@mui/x-data-grid';

import useHelper from 'src/hooks/useHelper';
import { DetailMeter } from 'src/sections/meter/DialogMeter';
import FormDialog from 'src/components/dialog/FormDialog'
import { useForm, Controller } from "react-hook-form";
import { ChangeEvent, ReactNode } from 'react';

export default function List() {  
    const { translate } = useLocales();
    
    const [parameter, setParameter] = useState({
        "search": "",
        "setLimit": "",
        "status": "",
        "order": "desc",
        "sortBy" : "create_dtm"
    });

    const [open, setOpen] = useState(false);
    const [dataImage, setImage] = useState('');
    const [message, setMessage] = useState('');
    const [recording_id, setRecordingId] = useState('');
    const [remark, setRemark] = useState('');
    const [values, setValues] = useState(0);
    const [dValue, setDValues] = useState(0);
    const [initValue, setInitValue] = useState(0);
    const [filterStatus, setFilterStatus] = useState('');
    const [dataStatus, setDataStatus] = useState<any[]>([]);
    // const { setLoadingShowBackdrop, showSnackbar } = useHelper();
    const [showBackdrop, setShowBackdrop] = useState(false);
    const [showSnackbar, setShowSnackbar] = useState(false);
    const [openDetailDialog, setOpenDetailDialog] = useState(false);
    const [openApprovalDialog, setOpenApprovalDialog] = useState(false);
    const [openDeclineDialog, setOpenDeclineDialog] = useState(false);
    const [selectedRow, setSelectedRow] = useState([] as any);
    const [disabled, setDisabled] = useState(true);

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    };  

    const defcolumns = [
        {
            field: 'no',
            width: 50,
            headerName: translate("#"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
            pinned: 'left'
        },
        {
            field: 'customer_name',
            headerName: translate("Customer Name"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
            pinned: 'left'
        },
        {
            field: 'nomor_meteran',
            headerName: translate("Registration Number"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
        },
        {
            field: 'status_name',
            headerName: translate("Status Name"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false
        },
        {
            field: 'period',
            headerName: translate("Period"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false
        },
        {
            field: 'create_dtm',
            headerName: translate("Request Date"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false
        },
        {
            field: 'update_dtm',
            headerName: translate("Update Date"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false
        },
        {
            field: 'action',
            headerName: translate("Action"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
            pinned: 'right'
        },
    ]

    const loadFilter = async (status:any, asc:any) => {
        setParameter({
            "search": "",
            "setLimit": "",
            "status": status,
            "order": asc,
            "sortBy" : asc ? "customer_name" : "create_dtm"
        })
    };

    const handleOpenDialog = async (e:any) => {
        try {
            setOpenDetailDialog(true)
            setShowBackdrop(true);
            setShowBackdrop(false);
            setSelectedRow(e);
        } catch (error) {
            setShowBackdrop(false);
            setShowSnackbar(true);
        }
    };

    const handleOpenApproval = async (e:any) => {
        try {
            setOpenApprovalDialog(true)
            setShowBackdrop(true);
            setShowBackdrop(false);
            setSelectedRow(e);
        } catch (error) {
            setShowBackdrop(false);
            setShowSnackbar(true);
        }
    };
    
    const handleOpenDecline = async (e:any) => {
        try {
            setOpenDeclineDialog(true)
            setShowBackdrop(true);
            setShowBackdrop(false);
            setSelectedRow(e);
        } catch (error) {
            setShowBackdrop(false);
            setShowSnackbar(true);
        }
    };

    useEffect(() => {
        if (filterStatus) {
            loadFilter(filterStatus, '');
        }
    }, [filterStatus]);

    const onSubmit = () => {
        loadFilter(filterStatus, '');
    }

    const openDialog = () => {
        handleClickOpen();
    }

    const onSubmitFilter = (val:any, e:any, ) => {
        e.preventDefault();
        loadFilter(val.status, val.asc);
        setFilterStatus(val.status);
        handleClose();
    }

    const defaultValues = {
        sort: "asc",
        status: "1",
    };

    const { register, handleSubmit, reset, control, getValues, setValue, watch } = useForm({
        defaultValues
    });

    const isFormDirty = () => {
        const formValues:any = getValues();
        const validate = formValues.sort !== defaultValues.sort || formValues.status !== defaultValues.status;
        setDisabled(!validate);
    };

    return (
        <Page title={personalization.application +" - " + translate('CMM') } container={false}>
            <Box sx={{ width: '100%', mb: 2, backgroundColor: '#fff', mt:4 }}>
                <Grid container>
                    <Grid item xs={6}>
                        <Typography variant="h5" fontWeight={500}>{translate("CMM Queue")}</Typography>
                    </Grid>
                    <Grid item xs={6} sx={{ textAlign: 'right', mt:1 }}>
                        <Typography variant={'body2'} sx={{ color: '#637381;' }}> {translate("CMM")} / {translate("Queue")}</Typography>
                    </Grid>
                </Grid>
            </Box>
            <Box sx={{ width: '100%', pb:0, backgroundColor: '#fff', mb:3 }}>
                <Datatable
                    load={[]}
                    addonParam={parameter}
                    columns={defcolumns}
                    mobileOptions={
                        {
                            visible: true,
                            mainColumns: defcolumns,
                            detailColumns: defcolumns
                        }
                    }
                    openDialog={openDialog}
                    components={{
                        Toolbar: GridToolbar,
                    }}
                />
            </Box>
            <FormDialog
                handleClose={handleClose}
                open={open}
                cancelButtonLabel={ translate ("DISMISS") }
                submitButtonLabel={ translate ("FILTER") }
                resetButtonLabel={ translate ("RESET") }
                handleSubmit={handleSubmit(onSubmitFilter)}
                reset={reset}
                title={ translate("Filter data") }
                maxWidth={'xs'}
            >
                <Box sx={{ width: '100%' }} component="form" noValidate autoComplete="off">
                    <Stack>
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
                                            isFormDirty();
                                        }}
                                    >
                                        <MenuItem value={'asc'}>{ translate ("Name A - Z") }</MenuItem>
                                        <MenuItem value={'desc'}>{ translate ("Name Z - A") }</MenuItem>
                                    </Select>
                                </FormControl>
                            )}
                        />
                    </Stack>

                    <Stack mt={2}>
                        <Controller
                            name="status"
                            control={control}
                            defaultValue={defaultValues.status}
                            render={({ field }) => (
                                <FormControl fullWidth>
                                    <InputLabel id="demo-simple-select-label-status"></InputLabel>
                                    <Select
                                        labelId="demo-simple-select-label-status"
                                        id="demo-simple-select-status"
                                        {...field}
                                        onChange={(event: SelectChangeEvent<string>, child: ReactNode) => {
                                            field.onChange(event.target.value)
                                            isFormDirty();
                                        }}
                                    >
                                        {dataStatus.map((status:any) => (
                                            <MenuItem key={status.id} value={status.id}>
                                                {status.status_name}
                                            </MenuItem>
                                        ))}
                                    </Select>
                                </FormControl>
                            )}
                        />
                    </Stack>
                </Box>
            </FormDialog>
            <DetailMeter 
                openModal={openDetailDialog} 
                closeModal={() => setOpenDetailDialog(false)}
                stateBackdrop={showBackdrop}
                stateSnackbar={showSnackbar}
                data={selectedRow}
                dataStatus={dataStatus}
                onSubmit={onSubmit}
            />
        </Page>
    );
}