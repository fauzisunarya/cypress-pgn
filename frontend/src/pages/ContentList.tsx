/* eslint-disable react/jsx-key */
import { useEffect, useState } from "react";
import { Box, Grid, Typography, MenuItem, Link, Stack, Button, FormControl, InputLabel, Select, Backdrop, Snackbar, CircularProgress } from "@mui/material";
import {
  DataGrid,
  GridRowId,
  GridColumns,
  GridRowParams,
  GridActionsCellItem,
} from '@mui/x-data-grid';
import DeleteIcon from '@mui/icons-material/Delete';
import SecurityIcon from '@mui/icons-material/Security';
import FileCopyIcon from '@mui/icons-material/FileCopy';
import Datatable, { saveOptionProps } from 'src/components/datatables/Datatable';
import Page from 'src/components/Page';
import { save } from 'src/api_handler/person';
import { load } from 'src/api_handler/users';
import { useLocales } from 'src/locales';
import { personalization } from 'src/config';
import moment from 'moment';
import { ChangeEvent, ReactNode } from 'react';
import { SelectChangeEvent } from '@mui/material';
import FormDialog from 'src/components/dialog/FormDialog';
import { useForm, Controller } from "react-hook-form";

export default function ContentList() {  
    const { translate }:any = useLocales();
    const [open, setOpen] = useState(false);
    const [openCreate, setOpenCreate] = useState(false);
    const [parameter, setParameter] = useState({
        "search": "",
        "setLimit": "",
        "status": "",
        "order": "desc",
        "sortBy" : "create_dtm"
    });

    const dataStatus = [
        {
            id : 1,
            name : 'Open'
        },
        {
            id : 2,
            name : 'Close'
        }
    ];

    const columns = [
        { field: 'no', headerName: '#', width: 90 },
        {
            field: 'name',
            headerName: 'Name',
            width: 150,
            editable: true,
        },
        {
            field: 'module',
            headerName: 'Module',
            width: 100,
            editable: true,
        },
        {
            field: 'Status',
            headerName: 'Phone Number',
            type: 'number',
            width: 120,
            editable: true,
        },
        
        {
            field: 'create_dtm',
            headerName: translate("Request Date"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
            renderCell : (params:any) => {
                return moment(params.row.create_dtm, 'YYYYMM').format('DD MMM YYYY hh:mm');
            }
        },
        {
            field: 'update_dtm',
            headerName: translate("Update Date"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
            renderCell : (params:any) => {
                return moment(params.row.update_dtm, 'YYYYMM').format('DD MMM YYYY hh:mm');
            }
        },
        {
            field: 'action',
            headerName: translate("Action"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
        },
    ];

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    }; 

    // const openDialog = () => {
    //     handleClickOpen();
    // }

    const openDialogCreate = () => {
        setOpenCreate(true);
    }

    const closeDialogCreate = () => {
        setOpenCreate(false);
    }

    const onSubmitFilter = (val:any, e:any, ) => {
        e.preventDefault();
        // loadFilter(val.status, val.asc);
        handleClose();
    }

    const defaultValues = {
        sort: "asc",
        status: "1",
    };

    const { register, handleSubmit, reset, control, getValues, setValue, watch } = useForm({
        defaultValues
    });

    return (
        <Page title={personalization.application +" - " + translate('Manage CMS') } container={false}>
            <Box sx={{ width: '100%', mb: 2, backgroundColor: '#fff', mt:4 }}>
                <Grid container>
                    <Grid item xs={6}>
                        <Typography variant="h5" fontWeight={500}>{translate("Manage CMS")}</Typography>
                    </Grid>
                    <Grid item xs={6} sx={{ textAlign: 'right', mt:1 }}>
                        <Typography variant={'body2'} sx={{ color: '#637381;' }}> {translate("CMS")} / {translate("Manage")}</Typography>
                    </Grid>
                </Grid>
            </Box>
            <Box sx={{ width: '100%', pb:0, backgroundColor: '#fff', mb:3 }}>
                <Datatable
                    load={[]}
                    addonParam={parameter}
                    columns={columns}
                    mobileOptions={
                        {
                            visible: true,
                            mainColumns: columns,
                            detailColumns: columns
                        }
                    }
                    buttonCreate={openDialogCreate}
                    // openDialog={openDialog}
                    // components={{
                    //     Toolbar: GridToolbar,
                    // }}
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
            <FormDialog
                handleClose={closeDialogCreate}
                open={openCreate}
                cancelButtonLabel={ translate ("DISMISS") }
                submitButtonLabel={ translate ("SAVE CHANGE") }
                handleSubmit={handleSubmit(onSubmitFilter)}
                reset={reset}
                title={ translate("Create Content") }
                maxWidth={'md'}
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
        </Page>
    );
}
