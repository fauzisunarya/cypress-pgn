// @mui
import CompactLayout from 'src/Layouts/compact/CompactLayout';
import Page from 'src/Components/Page';
import { Link, Box, Button, Checkbox, CircularProgress, Container, FormControl, FormControlLabel, Grid, InputLabel, MenuItem, Select, Stack, TextField, Typography, Alert, AlertTitle } from '@mui/material';
import { SelectChangeEvent } from '@mui/material';
import { ChangeEvent, ReactNode, useEffect, useMemo, useState } from 'react';

// components
import { PageProps } from 'src/types';
import Chip from 'src/Components/chip/Chip';
import Datatable from 'src/Components/datatables-v2/Datatable';
import { GridColDef } from '@mui/x-data-grid';
import FormDialog from 'src/Components/dialog/FormDialog';
import { Controller, useForm } from 'react-hook-form';
import { list } from 'src/api_handler/content';
import IconButton from 'src/Components/icon-button/IconButton'
import { useLocales } from 'src/locales';
import { CreatedDialog, DeleteDialog } from "src/sections/Content/Dialog";
import AuthGuard from 'src/auth/AuthGuard';
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
    const options: any = { day: 'numeric', month: 'short', year: 'numeric' };
    const tanggal = new Date(dateString).toLocaleDateString('id-ID', options);
    const jam = new Date(dateString).toLocaleTimeString('id-ID', { hour: 'numeric', minute: 'numeric' });
    return `${tanggal} ${jam}`;
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
            field: 'title',
            headerName: translate('Title'), 
            filterable: false,
            sortable: false,
            flex: 0.25,
        },
        {
            field: 'status',
            headerName: 'Status',
            filterable: false,
            sortable: false,
            flex: 0.2,
        },
        {
            field: 'created',
            headerName: translate('Create Date'), 
            filterable: false,
            sortable: false,
            flex: 0.3,
            renderCell : (params:any) => {
                return FormatDate(params.row.created);
            }
        },
        {
            field: 'updated',
            headerName: translate('Update Date'), 
            filterable: false,
            sortable: false,
            flex: 0.3,
            renderCell : (params:any) => {
                return FormatDate(params.row.updated);
            }
        },
        {
            field: "",
            headerName: translate('Action'), 
            filterable: false,
            sortable: false,
            flex: 0.35,
            renderCell: (params: any) => {
                return (
                    <Stack direction="row" spacing={1}>
                        <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }} onClick={(e:any) => handleEditDialog(params.row.uuid)}>{ translate('Edit') }</Link>
                        <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }} onClick={(e:any) => handleDeleteDialog(params.row.uuid)}>{ translate('Remove') }</Link>
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
                "sortBy": sortBy ? sortBy : 'nid',
                "order": order ? order : "desc",
                "setLimit": length,
                "search": search,
                "status": status,
                "setOffset" : "",
                "limit": "",
            })

            setLoading(false);
            setRows(response.data.data || []);
            setRowTotal(response.data.total_page || 0);
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
        setSelectedRow(value);
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
