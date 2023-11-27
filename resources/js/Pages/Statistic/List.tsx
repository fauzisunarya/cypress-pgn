import { PageProps } from '@/types';
import AuthGuard from '@/auth/AuthGuard';
import Page from '@/Components/Page';
import { Box, Grid, Stack, Typography, Skeleton } from '@mui/material';
import { useLocales } from '@/locales';
import { customerStatistic } from '@/api_handler/customer';
import { useEffect, useState } from 'react';

export default function List({ message }: PageProps<{ message: string }>) {
    const { translate } = useLocales();

    const [countUser, setCountUser] = useState('-');
    const [countAccount, setCountAccount] = useState('-');
    const [countRT, setCountRT] = useState('-');
    const [countPK, setCountPK] = useState('-');
    const [countRequest, setCountRequest] = useState('-');
    const [isLoading, setIsLoading] = useState(false);

    const fetchData = async () => {
        setIsLoading(true)
        try{
            const response: any = await customerStatistic({
                'type': "5",
                'startdate': "",
                'enddate': ""
            });
    
            setCountUser(response.data.data.user);
            setCountAccount(response.data.data.account);
            setCountRT(response.data.data.rt);
            setCountPK(response.data.data.pk);
            setCountRequest(response.data.data.request);
            setIsLoading(false)

        }catch(error){
            setIsLoading(false)
        }
    }

    useEffect(() => {
        fetchData()
    }, [])
    
    return (
        <AuthGuard>
            <Page title={'Statistic List'} container={false}>
                <Box sx={{ width: '100%', mb: 2, backgroundColor: '#fff', mt:4 }}>
                    <Grid container>
                        <Grid item xs={6}>
                            <Typography variant="h5" fontWeight={500}>{translate("Statistik Pelanggan")}</Typography>
                        </Grid>
                        <Grid item xs={6} sx={{ textAlign: 'right', mt:1 }}>
                            <Typography variant={'body2'} sx={{ color: '#637381;' }}> {translate("Statistic")} / {translate("List")}</Typography>
                        </Grid>
                    </Grid>
                </Box>
                <Grid container textAlign={'center'} pt={2}>
                    <Grid item xs={2.4} pr={2}>
                        <Stack spacing={2} sx={{ border: '1.5px solid #DADDE1', borderRadius: '10px', pt: 2, pb: 2 }}>
                            <Typography variant={'caption'}>{translate('Total User Mobile')}</Typography>
                                {isLoading?
                                    <Grid 
                                    sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
                                        <Skeleton width={'40px'} height={'40px'}/>
                                    </Grid>:
                                    <Typography variant={'h4'}>
                                        {countUser}
                                    </Typography>
                                }
                            <Typography variant={'caption'}>{translate('User')}</Typography>
                        </Stack>
                    </Grid>
                    <Grid item xs={2.4} pr={2}>
                        <Stack spacing={2} sx={{ border: '1.5px solid #DADDE1', borderRadius: '10px', pt: 2, pb: 2 }}>
                            <Typography variant={'caption'}>{translate('Total Pelanggan')}</Typography>
                            {isLoading?
                                <Grid 
                                sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
                                    <Skeleton width={'40px'} height={'40px'}/>
                                </Grid>:
                                <Typography variant={'h4'}>
                                    {countAccount}
                                </Typography>
                            }
                            <Typography variant={'caption'}>{translate('Pelanggan')}</Typography>
                        </Stack>
                    </Grid>
                    <Grid item xs={2.4} pr={2}>
                        <Stack spacing={2} sx={{ border: '1.5px solid #DADDE1', borderRadius: '10px', pt: 2, pb: 2 }}>
                            <Typography variant={'caption'}>{translate('Pelanggan Rumah Tangga')}</Typography>
                            {isLoading?
                                <Grid 
                                sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
                                    <Skeleton width={'40px'} height={'40px'}/>
                                </Grid>:
                                <Typography variant={'h4'}>
                                    {countRT}
                                </Typography>
                            }
                            <Typography variant={'caption'}>{translate('Pelanggan')}</Typography>
                        </Stack>
                    </Grid>
                    <Grid item xs={2.4} pr={2}>
                        <Stack spacing={2} sx={{ border: '1.5px solid #DADDE1', borderRadius: '10px', pt: 2, pb: 2 }}>
                            <Typography variant={'caption'}>{translate('Pelanggan Kecil')}</Typography>
                            {isLoading?
                                <Grid 
                                sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
                                    <Skeleton width={'40px'} height={'40px'}/>
                                </Grid>:
                                <Typography variant={'h4'}>
                                    {countPK}
                                </Typography>
                            }
                            <Typography variant={'caption'}>{translate('Pelanggan')}</Typography>
                        </Stack>
                    </Grid>
                    <Grid item xs={2.4} pr={2}>
                        <Stack spacing={2} sx={{ border: '1.5px solid #DADDE1', borderRadius: '10px', pt: 2, pb: 2}}>
                            <Typography variant={'caption'}>{translate('Jml Permintaan Pendaftaran')}</Typography>
                            {isLoading?
                                <Grid 
                                sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
                                    <Skeleton width={'40px'} height={'40px'}/>
                                </Grid>:
                                <Typography variant={'h4'}>
                                    {countRequest}
                                </Typography>
                            }
                            <Typography variant={'caption'}>{translate('Pelanggan')}</Typography>
                        </Stack>
                    </Grid>
                </Grid>
            </Page>
        </AuthGuard>
    )
}