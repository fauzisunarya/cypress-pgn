import { Box, Container, IconButton, Paper, Stack } from "@mui/material";
import Iconify from "@/Components/iconify";
import Logo from "@/Components/logo";

export default function Header(){
    return(
        <>
        <Paper elevation={1}>
            <Container sx={{py:2}}>
                <Stack direction="row"
                justifyContent="space-between"
                spacing={2}>
                    <IconButton><Iconify icon="fa6-solid:bars"/></IconButton>
                    <Logo />
                    {/* <Logo /> */}
                </Stack>
            </Container>
        </Paper>
        </>
    )
}