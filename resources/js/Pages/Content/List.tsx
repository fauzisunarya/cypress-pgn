// @mui
import Page from '@/Components/Page';
import { Link, Box, Button, Checkbox, CircularProgress, Container, FormControl, FormControlLabel, Grid, InputLabel, MenuItem, Select, Stack, TextField, Typography, Alert, AlertTitle } from '@mui/material';
import { SelectChangeEvent } from '@mui/material';
import { ChangeEvent, ReactNode, useEffect, useMemo, useState } from 'react';

// components
import Chip from '@/Components/chip/Chip';
import Datatable from '@/Components/datatables-v2/Datatable';
import { GridColDef } from '@mui/x-data-grid';
import FormDialog from '@/Components/dialog/FormDialog';
import { Controller, useForm } from 'react-hook-form';
import { list } from '@/api_handler/content';
import Iconify from '@/Components/iconify';
import { useLocales } from '@/locales';
import { CreatedDialog, DeleteDialog } from "@/sections/Content/Dialog";
import AuthGuard from '@/auth/AuthGuard';
// ----------------------------------------------------------------------

function filterData(params:any) {
    var order = '';
    var sortBy = '';

    if (params == '1') {
        order = 'asc';
        sortBy = 'title';
    } else if (params == '2') {
        order = 'desc';
        sortBy = 'title';
    } else if (params == '3') {
        order = 'asc';
        sortBy = 'status';
    } else if (params == '4') {
        order = 'desc';
        sortBy = 'status';
    } else if (params == '5') {
        order = 'asc';
        sortBy = 'created';
    } else if (params == '6') {
        order = 'desc';
        sortBy = 'created';
    } else if (params == '7') {
        order = 'asc';
        sortBy = 'created';
    } else if (params == '8') {
        order = 'desc';
        sortBy = 'created';
    }

    return [sortBy, order];
}

function FormatDate(dateString: any) {
    if (dateString) {
        const options: any = { day: 'numeric', month: 'short', year: 'numeric' };
        const tanggal = new Date(dateString).toLocaleDateString('id-ID', options);
        const jam = new Date(dateString).toLocaleTimeString('id-ID', { hour: 'numeric', minute: 'numeric' });
        return `${tanggal} ${jam}`;
    } else {
        return '';
    }
}

// export default function List({ message }: PageProps<{ message: string }>) {
export default function List() {
    const { translate } = useLocales();
    
    const [length, setLength] = useState(10);
    const [rows, setRows] = useState<any>([]);
    const [page, setPage] = useState<number>(1);
    const [rowTotal, setRowTotal] = useState<number>(0);
    const [loading, setLoading] = useState(true);
    const [open, setOpen] = useState(false);
    const [openCreate, setOpenCreate] = useState(false);
    const [openDelete, setOpenDelete] = useState(false);
    const [selectedRow, setSelectedRow] = useState('');

    const columns: GridColDef[] = [
        {
            field: 'id',
            headerName: translate('ID'), 
            filterable: false,
            sortable: false,
            flex: 0.1,
        },
        {
            field: 'name',
            headerName: translate('Title'), 
            filterable: false,
            sortable: false,
            flex: 0.4,
        },
        {
            field: 'status_name',
            headerName: translate('Status'), 
            filterable: false,
            sortable: false,
            flex: 0.3,
            renderCell : (params:any) => {
                if (params.row.status == 1) {
                    return (
                        <Stack>
                            <Chip label={'Active'} color={'success'} variant="outlined" />
                        </Stack>
                    );
                } else {
                    return (
                        <Stack>
                            <Chip label={'Inactive'} color={'error'} variant="outlined" />
                        </Stack>
                    );
                }
            }
        },
        {
            field: 'category_name',
            headerName: translate('Catagory'), 
            filterable: false,
            sortable: false,
            flex: 0.3,
        },
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
            flex: 0.1,
            renderCell: (params: any) => {
                return (
                    <Stack direction="row" spacing={1}>
                        <Iconify icon="fa:pencil" sx={{ color: 'text.disabled', width:16 }} style={{ cursor:'pointer' }} onClick={()=>handleEditDialog(params.row.id)}/>
                        <Iconify icon="fa:trash" sx={{ color: 'text.disabled', width:16 }} style={{ cursor:'pointer' }} onClick={()=>handleDeleteDialog(params.row.id)}/>
                    </Stack>
                );
            },
        }
    ];

    const handleEditDialog = async (e:any) => {
        setOpenCreate(true);
        setSelectedRow(e);
    };

    const handleDeleteDialog = async (e:any) => {
        setOpenDelete(true)
        setSelectedRow(e);
    };

    const handleLoadData = async (sortBy:any, order:any, search:any, status:any) => {
        setLoading(true);
        try {
            const response: any = await list({
                "page": page,
                "sortBy": sortBy ? sortBy : 'id',
                "order": order ? order : "desc",
                "setLimit": length,
                "search": search,
                "status": status,
                "setOffset" : "",
                "limit": "",
            });

            setLoading(false);
            setRows(response.data.data || []);
            setRowTotal(response.data.total || 0);
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
        handleLoadData('', '', '', '');
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

    const handleFilter = () => {
        setOpen(true);
    }

    const handleCreate = (value:any) => {
        setSelectedRow('0');
        setOpenCreate(true);
    }

    const handleClose = () => {
        setOpen(false);
    }; 

    const onSubmitFilter = (val:any, e:any) => {
        e.preventDefault();
        var fdata:any = filterData(val.sort);
        handleLoadData(fdata[0], fdata[1], '', '');
        handleClose();
    }

    const onSubmit = () => {
        const formValues:any = getValues();
        var fdata:any = filterData(formValues.sort);
        handleLoadData(fdata[0], fdata[1], formValues.search, '');
    }

    useEffect(() => {
        handleLoadData('', '', '', '');
    }, [page, length])

    const defaultValues = {
        sort: "0",
        search: "",
        // status: "1",
    };

    const { register, handleSubmit, reset, control, getValues, setValue, watch } = useForm({
        defaultValues
    });

    return (
        <Page title={'Station List'} container={false}>
            <Box sx={{ width: '100%', py: 2, backgroundColor: '#fff'}}>
                <Grid container>
                    <Grid item xs={6}>
                        <Typography variant="h5" fontWeight={500}>{translate("Manage Content")}</Typography>
                    </Grid>
                    <Grid item xs={6} sx={{ textAlign: 'right', mt:1 }}>
                        <Typography variant={'body2'} sx={{ color: '#637381;' }}> {translate("Content")} / {translate("Manage")}</Typography>
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
                    onClickAdd={handleCreate}
                />
            </Box>
            <CreatedDialog 
                openModal={openCreate} 
                closeModal={() => setOpenCreate(false)}
                stateBackdrop={true}
                data={selectedRow}
                onSubmit={onSubmit}
            />
            <DeleteDialog 
                openModal={openDelete} 
                closeModal={() => setOpenDelete(false)}
                stateBackdrop={true}
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
                            render={({ field }:any) => (
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
                                        <MenuItem value="5">{ translate ("Name A - Z") }</MenuItem>
                                        <MenuItem value="6">{ translate ("Name Z - A") }</MenuItem>
                                        <MenuItem value="7">{ translate ("Created Date A - Z") }</MenuItem>
                                        <MenuItem value="8">{ translate ("Created Date Z - A") }</MenuItem>
                                        <MenuItem value="9">{ translate ("Update Date A - Z") }</MenuItem>
                                        <MenuItem value="10">{ translate ("Update Date Z - A") }</MenuItem>
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
