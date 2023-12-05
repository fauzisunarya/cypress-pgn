import * as React from 'react';
import TextField from '@mui/material/TextField';
import { outlinedInputClasses } from '@mui/material/OutlinedInput';
import Box from '@mui/material/Box';
import { createTheme, ThemeProvider, Theme, useTheme } from '@mui/material/styles';

export default function CustomizedInputsStyleOverrides(props:any) {
    const theme = createTheme();

    return (
        <ThemeProvider theme={theme}>
            <TextField {...props}/>
        </ThemeProvider>
    );
}