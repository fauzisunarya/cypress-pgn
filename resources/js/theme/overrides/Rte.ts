import { Theme } from '@mui/material/styles';

// ----------------------------------------------------------------------

export default function RTE(theme: Theme) {
  return {
    MUIRichTextEditor: {
      styleOverrides: {
        root: {
          border: "1px solid "+theme.palette.grey[300],
          borderRadius: 12,
          height: 120
        },
        editor: {
          margin:6
        },
        placeholder: {
          margin:6
        },
      }
    }
  };
}
