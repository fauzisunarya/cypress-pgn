import { Theme } from '@mui/material/styles';
import { tableCellClasses } from '@mui/material/TableCell';

// ----------------------------------------------------------------------

export default function DataGrid(theme: Theme) {
  return {
    MuiDataGrid: {
      styleOverrides: {
        root: {
          '& .MuiDataGrid-columnHeaderTitle': {
            fontWeight: 'bold',
          },
          '& .MuiDataGrid-columnHeader': {
            backgroundColor: 'red',
            color: 'white',
          },
        }
      },
    },
  };
}
