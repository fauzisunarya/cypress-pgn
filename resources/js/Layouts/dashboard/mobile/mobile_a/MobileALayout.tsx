import { Box } from "@mui/material";
import { Outlet } from "react-router-dom";
import Header from "../../header/Header";
import Main from "../../Main";
import DefaultBottomNavigation from "../bottomNavigation/DefaultBottomNavigation";

export default function MobileALayout(props: any) {
    return (
        <>
            <Header />
            <Box
                sx={{
                display: { lg: 'flex' },
                minHeight: { lg: 1 },
                }}
            >

                <Main>
                    <Outlet />
                </Main>
            </Box>
            {(props.bottomNav) && (
            <DefaultBottomNavigation/>
            )}
        </>
    )
}