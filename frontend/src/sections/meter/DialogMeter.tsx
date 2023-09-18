import * as React from 'react';
import { Grid, Typography, Button, Dialog, DialogTitle, DialogContent, DialogActions, Box, Stack, TextField, FormControl, CircularProgress, Backdrop, Snackbar, InputAdornment, InputLabel, Select, MenuItem } from "@mui/material";
import { styled } from '@mui/material/styles';
import CloseIcon from '@mui/icons-material/Close';
import Iconify from 'src/components/iconify';
import IconButton from '@mui/material/IconButton';
import { useLocales } from 'src/locales';
import FormDialog from 'src/components/dialog/FormDialog';
import { ChangeEvent, ReactNode } from 'react';
import { SelectChangeEvent } from '@mui/material';
import Chip from 'src/components/chip/Chip';

import { useForm, Controller } from "react-hook-form";
import useHelper from "src/hooks/useHelper";

export interface DialogProps {
    openModal: boolean;
    closeModal: ()=>void;
    stateBackdrop: boolean;
    stateSnackbar: boolean;
    data:any;
    dataStatus:any;
    onSubmit:any;
}

function CustomerInformation(parameter : {dataCustomer:any, dataAddress:any, dataContact:any, dataIdentity:any}){
    const { translate } = useLocales();
    var dataCustomer = parameter.dataCustomer;
    var dataContact = parameter.dataContact;
    var dataAddress = parameter.dataAddress;
    var dataIdentity = parameter.dataIdentity;

    var phone = ''
    var email = '';
    if(dataContact){
        dataContact.forEach((contact: any) => {
            if(contact.contact_type == 5){
                phone = contact.contact_desc;
            }

            if(contact.contact_type == 4){
                email = contact.contact_desc;
            }
        });
    }
    return(
        <Stack sx={{ mt:-2, ml:-1 }}>
            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ translate ("Customer profile") }</Typography>
            <Typography variant={'bodyMedium'} sx={{ color:'#637381' }}>{ translate ("Below are detail of customer information that registered to the stand meter.") }</Typography>
            <Box sx={{ p: 2, border: '1px solid #DADDE1', borderRadius: '12px', mt:2 }}>
                <Grid container>
                    <Grid item xs={6}>
                        <Stack direction={'row'} spacing={2}>
                            <Typography variant={'bodyMedium'}>{ translate('Customer ID') }</Typography>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:500 }}>#{dataCustomer&& (dataCustomer as any).id}</Typography>
                        </Stack>
                    </Grid>
                    <Grid item xs={6} sx={{ textAlign: 'right' }}>
                        <Chip label={ translate('Verified') } color={'success'} sx={{ color:'#FFFFFF', borderRadius:'6px' }}/>
                    </Grid>
                </Grid>

                <Stack mt={1.5} spacing={1}>
                    <Stack direction={'row'} spacing={1}>
                        <Typography variant={'bodyMedium'} sx={{ fontWeight:500 }}>({dataCustomer&& (dataCustomer as any).gender})</Typography>
                        <Typography variant={'bodyMedium'} sx={{ fontWeight:500 }}>{dataCustomer&& (dataCustomer as any).customer_name}</Typography>
                    </Stack>
                    <Stack>
                        <Typography variant={'bodyMedium'}>{dataAddress&& (dataAddress as any)[0]['addr_desc']}</Typography>
                    </Stack>
                    <Stack direction={'row'}>
                        <Box sx={{ display: 'flex', alignItems: 'center',justifyContent: 'flex-start', borderRight: 1, borderColor: '##333435', pr:2 }}>
                            <Stack direction={'row'} spacing={1}>
                                <Iconify icon={'fa6-solid:tablet'} fontSize={'small'}/>
                                <Typography variant={'bodyMedium'}>{phone}</Typography>
                            </Stack>
                        </Box>
                        <Box sx={{ display: 'flex', alignItems: 'center',justifyContent: 'flex-start', pl:2 }}>
                            <Stack direction={'row'} spacing={1}>
                                <Iconify icon={'fa6-regular:envelope'} fontSize={'small'}/>
                                <Typography variant={'bodyMedium'}>hardcode@gmail.com</Typography>
                            </Stack>
                        </Box>
                    </Stack>
                </Stack>

                <Stack mt={1.5}>
                    <Grid container>
                        <Grid item xs={6}>
                            <Typography variant={'bodyMedium'}>{ translate('KTP (Kartu Tanda Penduduk)') }</Typography>
                        </Grid>
                        <Grid item xs={6} sx={{ textAlign: 'right' }}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:500 }}>1111111111</Typography>
                        </Grid>
                    </Grid>
                </Stack>

                <Stack direction={'row'} spacing={1} mt={1.5}>
                    <Grid container>
                        <Grid item xs={6}>
                            <Button variant="outlined" color={'inherit'} sx={{ width:'98%' }}>{ translate('Detail customer') }</Button>
                        </Grid>
                        <Grid item xs={6}>
                            <Button variant="outlined" color={'inherit'} sx={{ width:'98%' }}>{ translate('Transaction History') }</Button>
                        </Grid>
                    </Grid>
                </Stack>

                <Box sx={{ mt:1.5 }} display={'flex'} justifyContent={'center'} alignItems={'center'}>
                    <Typography variant={'bodyMedium'} sx={{ color:'#637381' }}>{ translate('This will open another tab to see detail of each information') }</Typography>
                </Box>
            </Box>
        </Stack>
    );
}

function ReporterInformation(parameter : {dataCustomer:any, dataAddress:any, dataContact:any, dataIdentity:any}){
    const { translate } = useLocales();
    var dataCustomer = parameter.dataCustomer;
    var dataContact = parameter.dataContact;
    var dataAddress = parameter.dataAddress;
    var dataIdentity = parameter.dataIdentity;

    var phone = ''
    var email = '';
    if(dataContact){
        dataContact.forEach((contact: any) => {
            if(contact.contact_type == 5){
                phone = contact.contact_desc;
            }

            if(contact.contact_type == 4){
                email = contact.contact_desc;
            }
        });
    }
    return(
        <Stack sx={{ mt:-2, ml:-1 }}>
            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ translate ("Reporter profile") }</Typography>
            <Typography variant={'bodyMedium'} sx={{ color:'#637381' }}>{ translate ("This report could be coming from the others account.") }</Typography>
            <Box sx={{ p: 2, border: '1px solid #DADDE1', borderRadius: '12px', mt:2 }}>
                <Grid container>
                    <Grid item xs={6}>
                        <Stack direction={'row'} spacing={2}>
                            <Typography variant={'bodyMedium'}>{ translate('Reporter ID') }</Typography>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:500 }}>#{dataCustomer&& (dataCustomer as any).id}</Typography>
                        </Stack>
                    </Grid>
                    <Grid item xs={6} sx={{ textAlign: 'right' }}>
                        <Chip label={ translate('Verified') } color={'success'} sx={{ color:'#FFFFFF', borderRadius:'6px' }}/>
                    </Grid>
                </Grid>

                <Stack mt={1.5} spacing={1}>
                    <Stack direction={'row'} spacing={1}>
                        <Typography variant={'bodyMedium'} sx={{ fontWeight:500 }}>({dataCustomer&& (dataCustomer as any).gender})</Typography>
                        <Typography variant={'bodyMedium'} sx={{ fontWeight:500 }}>{dataCustomer&& (dataCustomer as any).customer_name}</Typography>
                    </Stack>
                    <Stack>
                        <Typography variant={'bodyMedium'}>{dataAddress&& (dataAddress as any)[0]['addr_desc']}</Typography>
                    </Stack>
                    <Stack direction={'row'}>
                        <Box sx={{ display: 'flex', alignItems: 'center',justifyContent: 'flex-start', borderRight: 1, borderColor: '##333435', pr:2 }}>
                            <Stack direction={'row'} spacing={1}>
                                <Iconify icon={'fa6-solid:tablet'} fontSize={'small'}/>
                                <Typography variant={'bodyMedium'}>{phone}</Typography>
                            </Stack>
                        </Box>
                        <Box sx={{ display: 'flex', alignItems: 'center',justifyContent: 'flex-start', pl:2 }}>
                            <Stack direction={'row'} spacing={1}>
                                <Iconify icon={'fa6-regular:envelope'} fontSize={'small'}/>
                                <Typography variant={'bodyMedium'}>hardcode@gmail.com</Typography>
                            </Stack>
                        </Box>
                    </Stack>
                </Stack>

                <Stack mt={1.5}>
                    <Grid container>
                        <Grid item xs={6}>
                            <Typography variant={'bodyMedium'}>{ translate('KTP (Kartu Tanda Penduduk)') }</Typography>
                        </Grid>
                        <Grid item xs={6} sx={{ textAlign: 'right' }}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:500 }}>1111111111</Typography>
                        </Grid>
                    </Grid>
                </Stack>

                <Stack direction={'row'} spacing={1} mt={1.5}>
                    <Button variant="outlined" color={'inherit'} sx={{ width:'98%' }}>{ translate('Detail reporter') }</Button>
                </Stack>

                <Box sx={{ mt:1.5 }} display={'flex'} justifyContent={'center'} alignItems={'center'}>
                    <Typography variant={'bodyMedium'} sx={{ color:'#637381' }}>{ translate('This will open another tab to see detail of reporter profile.') }</Typography>
                </Box>
            </Box>
        </Stack>
    );
}

const ManageSubmission = () => {
    const { translate } = useLocales();
    return (
        <Stack direction={'column'} spacing={1.5}>
            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ translate('Manage submission') }</Typography>

            <Box sx={{ p: 2, border: '1px solid rgba(234, 179, 38, 0.08)', borderRadius: '12px', mt:2 }}>
                <Stack direction={'column'} sx={{ color:'#D49D0F' }}>
                    <Typography variant={'bodyMedium'}>{ translate('1. “Approve” this report submission if the value of meter are correct and eligible.') }</Typography>
                    <Typography variant={'bodyMedium'}>{ translate('2. Choose “Invalid” if there is information are not to clear enough and to tell the reporter/customer to do re-submission later.') }</Typography>
                    <Typography variant={'bodyMedium'}>{ translate('3. Or “Decline” this, to mark submission are not correct or not eligible') }</Typography>
                </Stack>
            </Box>
        </Stack>
    );
}

export const DetailMeter = (props: DialogProps) => {
    const { translate } = useLocales();

    return (
        <div>
            
        </div>
    )
}