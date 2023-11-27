// @mui
import CompactLayout from '@/Layouts/compact/CompactLayout';
import Page from '@/Components/Page';
import { Link, Box, Button, Checkbox, CircularProgress, Container, FormControl, FormControlLabel, Grid, InputLabel, MenuItem, Select, Stack, TextField, Typography, Alert, AlertTitle } from '@mui/material';
import { SelectChangeEvent } from '@mui/material';
import { ChangeEvent, ReactNode } from 'react';

// components
import { PageProps } from '@/types';
import Chip from '@/Components/chip/Chip';
import { Head } from '@inertiajs/react';
import Datatable from '@/Components/datatables-v2/Datatable';
import { useEffect, useMemo, useState } from 'react';
import { GridColDef } from '@mui/x-data-grid';
import FormDialog from '@/Components/dialog/FormDialog';
import { Controller, useForm } from 'react-hook-form';
import { list } from '@/api_handler/account';
import Iconify from '@/Components/iconify';
import { useLocales } from '@/locales';
import { Detail } from "@/sections/Account/Dialog";
import useHelper from '@/hooks/useHelper';
import { CreateDialog } from '@/sections/Account/CreateDialog';
// ----------------------------------------------------------------------

function filterData(params:any) {
    var order = '';
    var sortBy = '';

    if (params == '1') {
        order = 'asc';
        sortBy = 'nomor_pelanggan';
    } else if (params == '2') {
        order = 'desc';
        sortBy = 'nomor_pelanggan';
    } else if (params == '3') {
        order = 'asc';
        sortBy = 'status';
    } else if (params == '4') {
        order = 'desc';
        sortBy = 'status';
    } else if (params == '5') {
        order = 'asc';
        sortBy = 'create_dtm';
    } else if (params == '6') {
        order = 'desc';
        sortBy = 'create_dtm';
    } else if (params == '7') {
        order = 'asc';
        sortBy = 'update_dtm';
    } else if (params == '8') {
        order = 'desc';
        sortBy = 'update_dtm';
    }

    return [order, sortBy];
}

function FormatDate(dateString: any) {
    const options: any = { day: 'numeric', month: 'short', year: 'numeric' };
    const tanggal = new Date(dateString).toLocaleDateString('id-ID', options);
    const jam = new Date(dateString).toLocaleTimeString('id-ID', { hour: 'numeric', minute: 'numeric' });
    return `${tanggal} ${jam}`;
}

export default function List({ message }: PageProps<{ message: string }>) {
    const { translate } = useLocales();
    
    const [length, setLength] = useState(10);
    const [rows, setRows] = useState<any>([]);
    const [page, setPage] = useState<number>(1);
    const [rowTotal, setRowTotal] = useState<number>(0);
    const [loading, setLoading] = useState(true);
    const [open, setOpen] = useState(false);
    const [openView, setOpenView] = useState(false);
    const [selectedRow, setSelectedRow] = useState('');
    
    const [openDialogCreate, setOpenDialogCreate] = useState(false);

    const columns: GridColDef[] = [
        { 
            field: "no", 
            headerName: 'No', 
            filterable: false,
            sortable: false,
            flex: 0.1,
        },
        {
            field: 'phone_number',
            headerName: translate('Phone Number'), 
            filterable: false,
            sortable: false,
            flex: 0.3,
        },
        {
            field: 'email',
            headerName: translate('Email'), 
            filterable: false,
            sortable: false,
            flex: 0.4,
        },
        {
            field: 'nomor_pelanggan',
            headerName: translate('Customer ID'), 
            filterable: false,
            sortable: false,
            flex: 0.35,
        },
        {
            field: 'status',
            headerName: 'Status',
            filterable: false,
            sortable: false,
            flex: 0.3,
            renderCell : (params:any) => {
                if (params.row.status == 1) {
                    return (
                        <Stack>
                            <Chip label={'Request'} color={'primary'} variant="outlined" />
                        </Stack>
                    );
                } else if (params.row.status == 3) {
                    return (
                        <Stack>
                            <Chip label={'Reject'} color={'error'} variant="outlined" />
                        </Stack>
                    );
                } else if (params.row.status == 2) {
                    return (
                        <Stack>
                            <Chip label={'Approve'} color={'success'} variant="outlined" />
                        </Stack>
                    );
                } 
            }
        },
        // {
        //     field: 'update_by',
        //     headerName: translate('Processed By'), 
        //     filterable: false,
        //     sortable: false,
        //     flex: 0.3,
        // },
        {
            field: 'create_dtm',
            headerName: translate('Create Date'), 
            filterable: false,
            sortable: false,
            flex: 0.3,
            renderCell : (params:any) => {
                return FormatDate(params.row.create_dtm);
            }
        },
        {
            field: 'update_dtm',
            headerName: translate('Update Date'), 
            filterable: false,
            sortable: false,
            flex: 0.3,
            renderCell : (params:any) => {
                return FormatDate(params.row.update_dtm);
            }
        },
        {
            field: "",
            headerName: translate('Action'), 
            filterable: false,
            sortable: false,
            flex: 0.2,
            renderCell: (params: any) => {
                return (
                    <Stack direction="row" spacing={1}>
                        <Iconify icon="fa6-solid:circle-info" sx={{ color: 'text.disabled', width:20 }} style={{ cursor:'pointer' }} onClick={()=>handleOpenDialog(params.row.id)}/>
                    </Stack>
                );
            },
        }
    ];

    const handleLoadData = async (sortBy:any, order:any, search:any, status:any) => {
        setLoading(true);
        try {
            const response: any = await list({
                "page": page,
                "sortBy": sortBy,
                "order": order,
                "setLimit": length,
                "search": search,
                "status": status,
                "setOffset" : "",
                "limit": "",
            })

            setLoading(false);
            setRows(response.data.data.data || []);
            setRowTotal(response.data.data.total || 0);
        } catch (error) {
            // console.log(error);
        }
    }
    
    const handleChangeLength = (value: number) => {
        setLength(value);
    }

    const handlePageChange = (event: any, newPage: any) => {
        setPage(newPage)
    }

    const handleRefresh = () => {
        handleLoadData('', '', '', 1);
    }

    const handleSearch = (event:any) => {
        event.preventDefault();
        const key = event.key;
        const value = event.target.value;
        const valueFilter:any = getValues();
        var fdata:any = filterData(valueFilter.sort);

        const delayDebounceFn = setTimeout(() => {
            if (value.length > 2) {
                handleLoadData(fdata[0], fdata[1], value, '');
            } else {
                // if (key === 'Backspace' || key === 'Delete') {
                    // if (value.length <= 2) {
                        handleLoadData(fdata[0], fdata[1], '', '');
                    // }
                // }
            }

            setValue('search', value);
        }, 1500);

        return () => clearTimeout(delayDebounceFn);
    }

    const handleOpenDialog = async (e:any) => {
        setOpenView(true);
        setSelectedRow(e);
    };

    const handleFilter = () => {
        setOpen(true);
    }

    const handleClose = () => {
        setOpen(false);
    }; 

    const onSubmitFilter = (val:any, e:any) => {
        e.preventDefault();
        var fdata:any = filterData(val.sort);
        handleLoadData(fdata[0], fdata[1], '', val.status);
        handleClose();
    }

    const onSubmit = () => {
        const formValues:any = getValues();
        var fdata:any = filterData(formValues.sort);
        handleLoadData(fdata[0], fdata[1], formValues.search, formValues.status);
    }

    useEffect(() => {
        handleLoadData('', '', '', 1);
    }, [page, length])

    const defaultValues = {
        sort: "0",
        search: "",
        status: "1",
    };

    const { register, handleSubmit, reset, control, getValues, setValue, watch } = useForm({
        defaultValues
    });

    const closeModalCreate = () =>{
        setOpenDialogCreate(false);
    }
    
    const handleAdd = () =>{
        setOpenDialogCreate(true);
    }

    return (
        <Page title={'Customer to User Mapping Approval'} container={false}>
            <Box sx={{ width: '100%', backgroundColor: '#fff', py:2 }}>
                <Grid container>
                    <Grid item xs={6}>
                        <Typography variant="h5" fontWeight={500}>{translate("Customer to User Mapping Approval")}</Typography>
                    </Grid>
                    <Grid item xs={6} sx={{ textAlign: 'right', mt:1 }}>
                        <Typography variant={'body2'} sx={{ color: '#637381;' }}> {translate("Customer")} / {translate("User Mapping Approval")}</Typography>
                    </Grid>
                </Grid>
            </Box>
            <Box sx={{ width:'100%' }}>
                <Datatable
                    length={length}
                    isLoading={loading}
                    columns={columns}
                    rows={rows}
                    page={page}
                    rowTotal={rowTotal}
                    selectable={false}
                    onChangeLength={handleChangeLength}
                    onRefresh={handleRefresh}
                    onPageChange={handlePageChange}
                    onSearch={handleSearch}
                    onClickFilter={handleFilter}
                    onClickAdd={handleAdd}
                />
            </Box>
            <Detail 
                openModal={openView} 
                closeModal={() => setOpenView(false)}
                stateBackdrop={true}
                stateSnackbar={false}
                data={selectedRow}
                onSubmit={onSubmit}
            />
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
                                        <MenuItem value="1">{ translate ("Customer Number A - Z") }</MenuItem>
                                        <MenuItem value="2">{ translate ("Customer Number Z - A") }</MenuItem>
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
                                        <MenuItem value="1">{ translate('Request') }</MenuItem>
                                        <MenuItem value="2">{ translate('Approve') }</MenuItem>
                                        <MenuItem value="3">{ translate('Reject') }</MenuItem>
                                    </Select>
                                </FormControl>
                            )}
                        />
                    </Stack>
                </Box>
            </FormDialog>

            <CreateDialog
                openModal={openDialogCreate}
                closeModal={closeModalCreate}
            ></CreateDialog>
        </Page>
    );
}
