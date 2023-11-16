import * as React from 'react';
import Box from '@mui/material/Box';
import { DataGrid, GridColDef, GridRenderCellParams, } from '@mui/x-data-grid';
import { Button, MenuItem, Stack, TextField } from '@mui/material';
import Iconify from '../iconify/Iconify';
import { useLocales } from 'src/locales';
import Pagination from './Pagination';
import HeaderLoading from './loader/HeaderLoading';
import { CellLoading } from './loader/SkeletonItem';


const rows_default = [
    { id: 1 },
    { id: 2 },
    { id: 3 },
    { id: 4 },
    { id: 5 },
    { id: 6 },
    { id: 7 },
    { id: 8 },
    { id: 9 },
    { id: 10 }
];

export interface DatatableProps {
    length: number;
    isLoading: boolean;
    rows: Object[];
    columns: GridColDef[];
    page: number;
    handleRowSelection?: (val:any) => void;
    rowTotal: number;
    selectable ?: boolean;
    onChangeLength: (value: number) => void;
    onPageChange: (event: any, newPage: number) => void;
    onRefresh: () => void;
    onSearch: (event: any) => void;
    onClickFilter?: () => void;
    onClickAdd?: any;
};

export default function Datatable({
    length,
    isLoading,
    rows,
    columns,
    page,
    handleRowSelection,
    rowTotal,
    selectable,
    onChangeLength,
    onPageChange,
    onRefresh,
    onSearch,
    onClickFilter,
    onClickAdd,
}: DatatableProps) {
    const { translate } = useLocales();
    const [valueSearch, setValueSearch] = React.useState('');

    let columnsCustom = React.useMemo(() => {
        let data = [...columns];
        if(isLoading){
            data.map((v, key) => {
                data[key] = {
                    ...data[key],
                    renderCell: (params: GridRenderCellParams) => {
                        return <CellLoading />
                    }
                }
            });
            return data;
        }
        return columns;
    }, [isLoading]);

    let checkboxSelection = React.useMemo(() => {
        if (!selectable) return false;
        return !isLoading;
    },[isLoading])

    const NoRowsOverlay = () => {
        return (
            <Stack height="100%" alignItems="center" justifyContent="center">
                {translate('No information to show')}
            </Stack>
        );
    }

    return (
        <Box sx={{
            height: 400, width: '100%',
            '& .MuiDataGrid-cellCheckbox': {
                backgroundColor: '#f6f7f8',
                color: '#565758',
            },
            '& .MuiDataGrid-columnHeaderCheckbox': {
                borderRadius: 0
            },
        }}>
            {isLoading ? <HeaderLoading /> : (
                <Stack direction="row" sx={{ border: '1px solid #e5e7eb', p: 1 }}>
                    <Stack direction="row" spacing={1} >
                        <Button color="inherit" onClick={onRefresh}><Iconify icon="fa6-solid:arrow-rotate-right" /></Button>
                        <TextField
                            id="select-type"
                            value={length}
                            select
                            label=""
                            onChange={(e) => {
                                onChangeLength(parseInt(e.target.value))
                            }}
                            color="primary"
                            size="small">
                            <MenuItem key={1} value={10}>10</MenuItem>
                            <MenuItem key={2} value={25}>25</MenuItem>
                            <MenuItem key={3} value={50}>50</MenuItem>
                            <MenuItem key={4} value={100}>100</MenuItem>
                        </TextField>
                        <TextField size="small" onChange={(e: any) => { onSearch(e); setValueSearch(e.target.value) }} value={valueSearch} placeholder={translate("Search item")} />
                    </Stack>
                    <Stack direction="row" sx={{ ml: 'auto' }} spacing={1}>
                        {onClickFilter && 
                            <Button variant='outlined' color="inherit" onClick={onClickFilter} >{translate("Filter")}</Button>
                        }

                        {onClickAdd && 
                            <Button variant='contained' color="primary" onClick={onClickAdd} >{translate("Add New")}</Button>
                        }
                    </Stack>
                </Stack>
            )}
            <DataGrid
                autoHeight
                rows={isLoading ? rows_default : rows}
                columns={columnsCustom}
                initialState={{
                    pagination: {
                        paginationModel: {
                            pageSize: length,
                        },
                    },
                }}
                getRowId={(row:any) => row}
                pageSizeOptions={[10, 25, 50, 100]}
                checkboxSelection={checkboxSelection}
                disableColumnMenu 
                // disableRowSelectionOnClick
                onRowSelectionModelChange={handleRowSelection}
                components={{
                    NoRowsOverlay,
                    Pagination: () => <Pagination page={page} count={rowTotal} rowsPerPage={length} onPageChange={onPageChange} />
                }}
                sx={{
                    borderRadius: 0
                }}
            />
        </Box>
    );
}




