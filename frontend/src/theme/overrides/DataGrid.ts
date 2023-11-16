import { BorderRight } from '@mui/icons-material';
import { Theme } from '@mui/material/styles';
import { tableCellClasses } from '@mui/material/TableCell';

// ----------------------------------------------------------------------

export default function DataGrid(theme: Theme) {
  return {
    MuiDataGrid: {
      styleOverrides: {
        root: {
          '& .MuiDataGrid-root': {
            
            borderRadius: 0
          },
          '& .MuiDataGrid-columnHeaderTitle': {
            fontWeight: 'bold'
          },
          '& .MuiDataGrid-columnHeader': {
            backgroundColor: '#f6f7f8',
            color: '#565758',
            borderRight: '1px solid #e5e7eb',
          },
          '& .firstColumnHeader': {
            backgroundColor: '#f6f7f8',
            color: '#565758',
          },
          '& .lastColumnHeader': {
            borderRight: '0px',
          },
          '& .firstColumnCell': {
            backgroundColor: '#f6f7f8',
            color: '#565758',
            border: '1px solid #b1b2b3',
            borderBottom: '0px',
            fontWeight: 'bold',
            borderLeft: '0px'
          }
          ,
          '& .columnsCell': {
            color: '#565758',
            borderTop: '1px solid #b1b2b3',
            paddingTop: '10px',
            paddingBottom: '10px'
          },
          '& .lastColumnsCell': {
            color: '#565758',
            borderTop: '1px solid #b1b2b3',
          },
          '& .MuiDataGrid-iconSeparator': {
            display: 'none'
          },
          '& .MuiDataGrid-footerContainer': {
            borderTop: '1px solid #b1b2b3'
          },
          '& .MuiDataGrid-virtualScrollerContent': {
          }
        }
      },
    },
  };
}
