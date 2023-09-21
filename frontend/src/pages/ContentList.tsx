/* eslint-disable react/jsx-key */
import { useEffect, useState } from "react";
import { Box, Grid, Typography, MenuItem, TextField, Stack, Button, FormControl, InputLabel, Select, Link, Backdrop, Snackbar, CircularProgress } from "@mui/material";
import Datatable, { saveOptionProps } from 'src/components/datatables/Datatable';
import Page from 'src/components/Page';
import { useLocales } from 'src/locales';
import { personalization } from 'src/config';
import moment from 'moment';
import { ChangeEvent, ReactNode } from 'react';
import { SelectChangeEvent } from '@mui/material';
import FormDialog from 'src/components/dialog/FormDialog';
import { useForm, Controller } from "react-hook-form";
import TextEditor from "src/components/TextEditor";
import { list } from 'src/api_handler/content';

export default function ContentList() {  
    const { translate }:any = useLocales();
    const [open, setOpen] = useState(false);
    const [openCreate, setOpenCreate] = useState(false);
    const [parameter, setParameter] = useState({
        "search": "",
        "setLimit": "",
        "status": "",
        "order": "desc",
        "sortBy" : "nid"
    });

    const loadFilter = async (order:any, sortBy:any) => {
        setParameter({
            "search": "",
            "setLimit": "",
            "status": "",
            "order": order,
            "sortBy" : sortBy ? "title" : "nid"
        })
    };

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
        {
            field: 'title',
            headerName: translate('Title'),
            width: 150,
            filterable: false,
            sortable: false,
        },
        {
            field: 'status',
            headerName: 'Status',
            width: 120,
            filterable: false,
            sortable: false,
        },
        {
            field: 'created',
            headerName: translate("Created Date"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
            renderCell : (params:any) => {
                return moment(params.row.created, 'YYYYMM').format('DD MMM YYYY hh:mm');
            }
        },
        {
            field: 'changed',
            headerName: translate("Update Date"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
            renderCell : (params:any) => {
                return moment(params.row.changed, 'YYYYMM').format('DD MMM YYYY hh:mm');
            }
        },
        {
            field: 'action',
            headerName: translate("Action"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
            renderCell : (params:any) => {
                return (
                    <Stack direction="row" spacing={1}>
                        <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }}>{ translate('Edit') }</Link>
                        <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }}>{ translate('Remove') }</Link>
                    </Stack>
                );
            }
        },
    ];

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    }; 

    const openDialog = () => {
        handleClickOpen();
    }

    const openDialogCreate = () => {
        setOpenCreate(true);
    }

    const closeDialogCreate = () => {
        setOpenCreate(false);
    }

    const onSubmitFilter = (val:any, e:any) => {
        e.preventDefault();
        var order = '';
        var sortBy = '';

        if (val.sort == '1') {
            order = 'asc';
            sortBy = 'title';
        } else if (val.sort == '2') {
            order = 'desc';
            sortBy = 'title';
        } else if (val.sort == '3') {
            order = 'asc';
            sortBy = 'status';
        } else if (val.sort == '4') {
            order = 'desc';
            sortBy = 'status';
        } else if (val.sort == '5') {
            order = 'asc';
            sortBy = 'created';
        } else if (val.sort == '6') {
            order = 'desc';
            sortBy = 'created';
        } else if (val.sort == '7') {
            order = 'asc';
            sortBy = 'changed';
        } else if (val.sort == '8') {
            order = 'desc';
            sortBy = 'changed';
        }

        loadFilter(order, sortBy);
        handleClose();
    }

    const defaultValues = {
        sort: "0",
        // status: "1",
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
                    load={list}
                    addonParam={parameter}
                    columns={columns}
                    mobileOptions={
                        {
                            visible: true,
                            mainColumns: columns,
                            detailColumns: columns
                        }
                    }
                    primaryId={'nid'}
                    buttonCreate={openDialogCreate}
                    openDialog={openDialog}
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
                                        <MenuItem value="0">{ translate ("Choose Sort") }</MenuItem>
                                        <MenuItem value="1">{ translate ("Title A - Z") }</MenuItem>
                                        <MenuItem value="2">{ translate ("Title Z - A") }</MenuItem>
                                        <MenuItem value="3">{ translate ("Status A - Z") }</MenuItem>
                                        <MenuItem value="4">{ translate ("Status Z - A") }</MenuItem>
                                        <MenuItem value="5">{ translate ("Created Date A - Z") }</MenuItem>
                                        <MenuItem value="6">{ translate ("Created Date Z - A") }</MenuItem>
                                        <MenuItem value="7">{ translate ("Update Date A - Z") }</MenuItem>
                                        <MenuItem value="8">{ translate ("Update Date Z - A") }</MenuItem>
                                    </Select>
                                </FormControl>
                            )}
                        />
                    </Stack>

                    {/* <Stack mt={2}>
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
                    </Stack> */}
                </Box>
            </FormDialog>
        </Page>
    );
}
