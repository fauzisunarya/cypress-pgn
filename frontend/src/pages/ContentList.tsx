/* eslint-disable react/jsx-key */
import { useState } from "react";
import { Box, Grid, Typography, MenuItem, Stack, FormControl, InputLabel, Select, Link } from "@mui/material";
import Datatable from 'src/components/datatables/Datatable';
import Page from 'src/components/Page';
import { useLocales } from 'src/locales';
import { personalization } from 'src/config';
import { SelectChangeEvent } from '@mui/material';
import FormDialog from 'src/components/dialog/FormDialog';
import { useForm, Controller } from "react-hook-form";
import { list } from 'src/api_handler/content';
import useHelper from "src/hooks/useHelper";
import { CreatedDialog, DeleteDialog } from "src/sections/Content/Dialog";
import AuthGuard from "src/auth/AuthGuard";
import { ChangeEvent, ReactNode } from 'react';
import { GridColDef, GridValueGetterParams } from '@mui/x-data-grid';

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
        sortBy = 'changed';
    } else if (params == '8') {
        order = 'desc';
        sortBy = 'changed';
    }

    return [order, sortBy];
}

function FormatDate(dateString: any) {
    const options: any = { day: 'numeric', month: 'short', year: 'numeric' };
    const tanggal = new Date(dateString).toLocaleDateString('id-ID', options);
    const jam = new Date(dateString).toLocaleTimeString('id-ID', { hour: 'numeric', minute: 'numeric' });
    return `${tanggal} ${jam}`;
}

export default function ContentList() {  
    const { translate }:any = useLocales();
    const { setLoadingShowBackdrop, showSnackbar } = useHelper();
    const [open, setOpen] = useState(false);
    const [openCreate, setOpenCreate] = useState(false);
    const [openDelete, setOpenDelete] = useState(false);
    const [selectedRow, setSelectedRow] = useState('');
    const [parameter, setParameter] = useState({
        "search": "",
        "setLimit": "",
        "status": "",
        "order": "desc",
        "sortBy" : "nid"
    });

    const [length, setLength] = useState(10);
    const [rows, setRows] = useState<any>([]);
    const [page, setPage] = useState<number>(1);
    const [rowTotal, setRowTotal] = useState<number>(0);
    const [loading, setLoading] = useState(true);

    const columns: GridColDef[] = [
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
                return FormatDate(params.row.created);
            }
        },
        {
            field: 'changed',
            headerName: translate("Update Date"),
            cellClassName: 'columnsCell',
            filterable: false,
            sortable: false,
            renderCell : (params:any) => {
                return FormatDate(params.row.changed);
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
                        <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }} onClick={(e:any) => handleEditDialog(params.row.uuid)}>{ translate('Edit') }</Link>
                        <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }} onClick={(e:any) => handleDeleteDialog(params.row.uuid)}>{ translate('Remove') }</Link>
                    </Stack>
                );
            }
        },
    ];

    const handleLoadData = async (order:any, sortBy:any) => {
        setLoading(true);
        try {
            const response: any = await list({
                "search": "",
                "setLimit": "",
                "status": "",
                "order": order,
                "sortBy" : sortBy ? sortBy : "title"
            })

            setLoading(false);
            setRows(response.data.data.data);
            setRowTotal(response.data.data.total);

            // console.log('debugData', response.data.data.data)
        } catch (error) {
            console.log('debugErr', error)
        }
      }
    
    const handleChangeLength = (value: number) => {
        setLength(value);
    }

    const handlePageChange = (event: any, newPage: any) => {
        setPage(newPage)
    }

    const handleRefresh = () => {
        handleLoadData('', '');
    }

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    }; 

    const handleOpenDelete = () => {
        setOpenDelete(true);
    };

    const handleCloseDelete = () => {
        setOpenDelete(false);
    }; 

    const openDialog = () => {
        handleClickOpen();
    }

    const openDialogCreate = () => {
        setSelectedRow('');
        setOpenCreate(true);
    }

    const closeDialogCreate = () => {
        setOpenCreate(false);
    }

    const onSubmitFilter = (val:any, e:any) => {
        e.preventDefault();
        var fdata:any = filterData(val.sort);
        handleLoadData(fdata.order, fdata.sortBy);
        handleClose();
    }

    const handleEditDialog = async (e:any) => {
        try {
            setOpenCreate(true)
            setLoadingShowBackdrop(true);
            setLoadingShowBackdrop(false);
            setSelectedRow(e);
        } catch (error) {
            setLoadingShowBackdrop(false);
        }
    };

    const handleDeleteDialog = async (e:any) => {
        try {
            setOpenDelete(true)
            setLoadingShowBackdrop(true);
            setLoadingShowBackdrop(false);
            setSelectedRow(e);
        } catch (error) {
            setLoadingShowBackdrop(false);
        }
    };

    const defaultValues = {
        sort: "0",
        // status: "1",
    };

    const handleSearch = () => {

    }

    const { register, handleSubmit, reset, control, getValues, setValue, watch } = useForm({
        defaultValues
    });

    const onSubmit = () => {
        const formValues:any = getValues();
        // var filterData:any = filterData(formValues.sort);
        // loadFilter(filterData.order, filterData.sortBy);
        handleLoadData('', '');
    }

    return (
        // <AuthGuard>
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
                        // onClickFilter={handleFilter}
                        // onClickAdd={handleAdd}
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
        // </AuthGuard>
    );
}
