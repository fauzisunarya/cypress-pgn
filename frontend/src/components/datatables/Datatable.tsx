import { LoadingButton } from "@mui/lab";
import { Stack, TextField, Typography, Grid, Button, MenuItem, TablePaginationProps, Box, InputAdornment, Skeleton } from "@mui/material";
import { DataGrid, GridActionsCellItem, GridColDef, GridPagination, GridRowParams, gridClasses, GridFooter } from "@mui/x-data-grid";
import { useTheme } from '@mui/material/styles';
import SearchIcon from '@mui/icons-material/Search';
import { useEffect, useState } from "react";
import { DatatableProvider } from "src/contexts/DatatableContext";
import useDatatable from "src/hooks/useDatatable";
import useResponsive from "src/hooks/useResponsive";
import Iconify from "../iconify";

import FormDialog from "./FormDialog";
import ItemList from "./ItemList";
import { useSnackbar } from "notistack";
import MuiPagination from '@mui/material/Pagination';
import { useLocales } from 'src/locales';

export type fillableProps = {
  field: string;
  type: string;
  options?: Object;
}
export type saveOptionProps = {
  handleSave: (body?: any) => void;
  handleEdit?: any;
  fillable: fillableProps[];
}
export type mobileOptionsProps = {
  visible: boolean;
  mainColumns?: any;
  detailColumns?: any;
}
export default function Datatable(props: {
  load: any;
  columns: GridColDef[];
  addonParam?: any;
  getActions?: (row: any) => any;
  searchAction?: (keyword: string) => void;
  limitAction?: (limit: any) => void;
  primaryId?: string;
  mobileOptions?: mobileOptionsProps;
  saveOption?: saveOptionProps;
  searching?:boolean;
  openDialog?:any;
  components?:any;
}) {
  const { columns, mobileOptions, load, saveOption, addonParam, primaryId, getActions,  searching = true, openDialog, components } = props;

  return (
    <DatatableProvider>
      <TableView load={load} addonParam={addonParam} primaryId={primaryId} getActions={getActions} searchAction={props.searchAction} limitAction={props.limitAction} columns={columns} mobileOptions={mobileOptions} saveOption={saveOption} searching={searching} openDialog={openDialog} components={components}/>
    </DatatableProvider>
  )
}
function TableView(props: {
  load: any;
  columns: any;
  addonParam?: any;
  primaryId?: string;
  getActions?: (row: any) => any;
  searchAction?: (keyword: string) => void;
  limitAction?: (limit: any) => void;
  mobileOptions?: mobileOptionsProps;
  saveOption?: saveOptionProps;
  searching?: boolean;
  openDialog?:any;
  components?:any;
}) {
  const { translate } = useLocales();
  const isDesktop = useResponsive('up', 'lg');
  const { columns, mobileOptions, saveOption, addonParam, primaryId = "id", searching = true, openDialog, components } = props;
  if (mobileOptions != undefined) {
    const { visible, mainColumns, detailColumns } = mobileOptions;
  }
  const { data, page, rowCount, rowTotal, keyword, limit, sort, reload, setSort, setData, setPage, setRowCount, setRowTotal, setKeyword, setLimit, setReload } = useDatatable();
  const { enqueueSnackbar } = useSnackbar();
  const theme = useTheme();
  useEffect(() => {
    const fetch = async () => {
      setData({
        ...data,
        isLoading: true,
        data: [],
        code: null,
        info: 'waiting'
      });
      
      let param: any = {
        "page": page == 0 ? page + 1 : page,
        "limit": rowCount,
        "search": keyword,
        "setLimit": limit,
      }
      // let param = {};
      if (props.limitAction == undefined) {
        param.setLimit = limit;
      }

      if (props.searchAction == undefined) {
        param.search = keyword;
      }

      if (sort?.length > 0) {
        param.sortBy = sort[0].field
        param.order = sort[0].sort
      }

      if (addonParam !== undefined) {
        param = { ...addonParam, ...param };
      }
      try {
        const response: any = await props.load(param);
        setRowTotal(response.data.data.total);
        setData({
          ...data,
          isLoading: false,
          data: response.data.data.data ? response.data.data.data : [],
          code: 0,
          info: 'Success'
        });
      } catch (e) {
        setData({
          ...data,
          isLoading: false,
          data: [],
          code: 0,
          info: 'Success'
        });
      }
    }
    fetch()
  }, [page, addonParam, rowCount, keyword, limit, sort, reload])
  columns.map((v: any) => {
    if(v.headerName != '#'){
      v.flex = 1;
    }
  })

  const [length, setLength] = useState(10);
  const [openForm, setOpenForm] = useState(false);
  const [formMode, setFormMode] = useState('save');
  const [currentRow, setCurrentRow] = useState(null);

  const handleClickOpenForm = (id: string | number | null = null, row: any = null) => {
    setCurrentRow(null)
    if (row) setCurrentRow(row)
    setFormMode(id ? 'update' : 'save');
    setOpenForm(true);
  };

  const handleCloseForm = () => {
    setOpenForm(false);
  };

  const NoRowsOverlay = () => {
    return (
      <Stack height="100%" alignItems="center" justifyContent="center">
        {translate('No rows')}
      </Stack>
    );
  }

  const openDialogFilter = () => {
    openDialog();
  }

  return (
    <div style={{width: '100%', border: '1px solid #DADDE1' }}>
      <Stack direction="row" justifyContent={'space-between'} sx={{padding:'8px'}} >
        <Grid container>
          <Grid item xs={6}>
            <Stack direction="row" spacing={1}>
              <Button color="inherit" size="small" onClick={setReload} style={{ marginTop: "5px", color: '#6e7d8b' }} disabled={data.isLoading}><Iconify icon="fa6-solid:arrow-rotate-right" /></Button>
              <TextField
                id="select-type"
                value={length}
                select
                label=""
                color="primary"
                size="small"
                InputProps={{
                  readOnly : data.isLoading
                }}
                onChange={(val: any) => {
                  setLength(val.target.value)
                  if (props.limitAction !== undefined) {
                    props.limitAction(val.target.value)
                  }

                  setLimit(val.target.value)
                }}>
                <MenuItem key={1} value={10}>10</MenuItem>
                <MenuItem key={2} value={25}>25</MenuItem>
                <MenuItem key={3} value={50}>50</MenuItem>
                <MenuItem key={4} value={100}>100</MenuItem>
              </TextField>
              {searching && <TextField value={keyword} size="small" onChange={(val: any) => {
                if (props.searchAction !== undefined) {
                  props.searchAction(val.target.value)
                }

                setKeyword(val.target.value)

              }
              } placeholder={translate("Search item")} InputProps={{
                endAdornment: (
                  <InputAdornment position="end">
                    <SearchIcon />
                  </InputAdornment>
                ),
              }}/>}
            </Stack>
          </Grid>
          <Grid item xs={6} sx={{ textAlign:'text-right' }}>
            <Box display={'flex'} justifyContent={'right'} alignItems={'right'} sx={{ mr: 3 }}>
              <Button variant="outlined" color={'inherit'} onClick={openDialogFilter} disabled={data.isLoading}>
                { translate('Filter list') }
              </Button>
            </Box>
          </Grid>
        </Grid>
      </Stack>
      {(isDesktop || mobileOptions == undefined) && (
        <DataGrid
          autoHeight {...data.data}
          rows={data.data ? data.data : []}
          rowCount={rowTotal}
          loading={data.isLoading}
          rowsPerPageOptions={[10]}
          getRowId={(row) => row[primaryId]}
          disableColumnMenu={true}
          pagination
          sortingMode="server"
          onSortModelChange={(val) => { setSort(val) }}
          components={{
            NoRowsOverlay,
            Pagination : () => <Pagination page={page} count={rowTotal} rowsPerPage={length} onPageChange={(event: any, newPage: any) => setPage(newPage)}/> 
          }}
          page={page}
          pageSize={rowCount}
          paginationMode="server"
          onPageSizeChange={(newPageSize: any) => setRowCount(newPageSize)}
          columns={columns}
          getRowHeight={() => 'auto'}
        />
      )}
      {(!isDesktop && mobileOptions != undefined) && (
        <Stack sx={{ width: "100%" }} spacing={2}>
          {(data?.length > 0) && (data).map((val: any, i: number) => (
            <ItemList value={val} mainColumns={mobileOptions?.mainColumns} detailColumns={mobileOptions?.detailColumns} key={i} />
          ))}
        </Stack>
      )}
      {(openForm) && (<FormDialog open={openForm} handleClose={handleCloseForm} formMode={formMode} saveOption={saveOption} currentRow={currentRow} />)}
    </div>
  )
}
function Pagination({
  page,
  count,
  rowsPerPage,
  onPageChange,
  className,
}: Pick<TablePaginationProps, 'page' | 'count' | 'rowsPerPage' | 'onPageChange' | 'className'>) {
  const { translate } = useLocales();
  var startRow = (page*rowsPerPage)-rowsPerPage+1;
  var endRow = page*rowsPerPage;
  startRow = (startRow > count)? count : startRow;
  endRow = (endRow > count)? count : endRow;
  const theme = useTheme();

  const customColor = {
    color: theme.palette.primary.light,
  };

  return (
    <Box sx={{ display:"flex", width:'100%', justifyContent:'space-between'}}>
      <Typography className="MuiTablePagination-displayedRows" sx={{ paddingTop:'3px', marginLeft:2}}>
      {translate('Showing')} {startRow} - {endRow} {translate('of')} {count} {translate('entries')}
      </Typography>
      <MuiPagination
        color="primary"
        className={className}
        count={Math.ceil(count/rowsPerPage)}
        page={page}
        onChange={(event, newPage) => {
          onPageChange(event as any, newPage);
        }}
      />
    </Box>
  );
}

