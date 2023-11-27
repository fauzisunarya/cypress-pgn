import { useLocales } from "@/locales";
import { Box, TablePaginationProps, Typography } from "@mui/material";
import MuiPagination from '@mui/material/Pagination';

export default function Pagination({
    page,
    count,
    rowsPerPage,
    onPageChange,
    className,
}: Pick<TablePaginationProps, 'page' | 'count' | 'rowsPerPage' | 'onPageChange' | 'className'>) {
    const { translate } = useLocales();
    var startRow = (page * rowsPerPage) - rowsPerPage + 1;
    var endRow = page * rowsPerPage;
    startRow = (startRow > count) ? count : startRow;
    endRow = (endRow > count) ? count : endRow;
    return (
        <Box sx={{ display: "flex", width: '100%', justifyContent: 'space-between' }}>
            <Typography className="MuiTablePagination-displayedRows" sx={{ paddingTop: '3px', marginLeft: 2 }}>
                {translate('Showing')} {startRow} - {endRow} {translate('of')} {count} {translate('entries')}
            </Typography>
            <MuiPagination
                color="primary"
                className={className}
                count={Math.ceil(count / rowsPerPage)}
                page={page}
                onChange={(event, newPage) => {
                    onPageChange(event as any, newPage);
                }}
            />
        </Box>
    );
}