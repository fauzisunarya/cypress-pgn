import { Skeleton, Stack } from "@mui/material";

export default function HeaderLoading() {
    return (
        <Stack direction="row" sx={{ border: '1px solid #e5e7eb', p: 1 }}>
            <Stack direction="row" spacing={1} >
                <Skeleton variant="rectangular" width={80} height={36} />
                <Skeleton variant="rectangular" width={80} height={36} />
                <Skeleton variant="rectangular" width={180} height={36} />
            </Stack>
            <Stack direction="row" sx={{ ml: 'auto' }} spacing={1}>
                <Skeleton variant="rectangular" width={80} height={36} />
                <Skeleton variant="rectangular" width={80} height={36} />
            </Stack>
        </Stack>
    )
}