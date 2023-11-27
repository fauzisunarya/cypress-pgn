import * as React from 'react';
import { Grid, Typography, Button, Menu, DialogTitle, DialogContent, DialogActions, Box, Stack, TextField, FormControl, CircularProgress, Backdrop, Snackbar, InputAdornment, InputLabel, Select, MenuItem } from "@mui/material";
import { styled } from '@mui/material/styles';
import CloseIcon from '@mui/icons-material/Close';
import Iconify from '@/Components/iconify';
import IconButton from '@mui/material/IconButton';
import { useLocales } from '@/locales';
import FormDialog from '@/Components/dialog/FormDialog';
import { ChangeEvent, ReactNode } from 'react';
import { SelectChangeEvent } from '@mui/material';
import Chip from '@/Components/chip/Chip';
// import TextField from '@/Components/textfield/TextField';

import { detail, update, dummyApi, deleteRequest } from "@/api_handler/account";

import moment from "moment"
import { any } from 'prop-types';
import { useForm, Controller, useWatch, Control } from "react-hook-form";
import useHelper from "@/hooks/useHelper"
import { service } from '../../config';

function FormatDate(dateString: any) {
    const options: any = { day: 'numeric', month: 'short', year: 'numeric' };
    const tanggal = new Date(dateString).toLocaleDateString('id-ID', options);
    const jam = new Date(dateString).toLocaleTimeString('id-ID', { hour: 'numeric', minute: 'numeric' });
    return `${tanggal} ${jam}`;
}

export interface DialogProps {
    openModal: boolean;
    closeModal: ()=>void;
    stateBackdrop: boolean;
    stateSnackbar?: boolean;
    data:any;
    onSubmit:any;
}

interface typeCRM {
    noref?: string;
    phone_number?: string;
    name?: string;
    nik?: string;
    district?: string; 
    serial_number?: string;
}

function CustomerInformation(parameter : {dataAll:any}){
    const { translate } = useLocales();
    var dataCustomer = parameter.dataAll.customer;
    var dataRequest = parameter.dataAll.request;
    var dataDocument = parameter.dataAll.document;
    var defaultImg = 'https://i.ibb.co/Rvvx9jq/Image-not-available.png';
    
    return(
        <Stack mt={2}>
            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ translate ("User") }</Typography>
            <hr style={{ borderTop: '1px dotted #DADDE1', width: '100%' }} />
            <Stack direction={'column'} mt={2} spacing={2}>
                <Grid container>
                    <Grid item xs={4}>
                        <Typography variant={'bodyMedium'}>{ translate('Phone Number') }</Typography>
                    </Grid>
                    <Grid item xs={8}>
                        <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataRequest?.phone_number}</Typography>
                    </Grid>
                </Grid>

                <Grid container>
                    <Grid item xs={4}>
                        <Typography variant={'bodyMedium'}>{ translate('Email') }</Typography>
                    </Grid>
                    <Grid item xs={8}>
                        <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataRequest?.email}</Typography>
                    </Grid>
                </Grid>

                <Grid container>
                    <Grid item xs={4}>
                        <Typography variant={'bodyMedium'}>{ translate('Request Date') }</Typography>
                    </Grid>
                    <Grid item xs={8}>
                        <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {FormatDate(dataRequest?.create_dtm)}</Typography>
                    </Grid>
                </Grid>
            </Stack>
        </Stack>
    );
}

function ReporterInformation(parameter : {dataAll:any, setMessages:any}){
    const { translate } = useLocales();
    var noref = parameter.dataAll.nomor_pelanggan;
    const [dataCust, setDataCust] = React.useState<typeCRM | null>(null);
    const { setLoadingShowBackdrop, showSnackbar } = useHelper();

    const getCustomerInfo = async () => {
        if (noref) {
            setLoadingShowBackdrop(true)
            const response: any = await dummyApi({nomor_pelanggan:noref});
            var responseData = response.data.data;
            if (response.data.code == 0) {
                setDataCust(responseData);
            } else {
                setDataCust(null);
                showSnackbar({message:response.data.info});
            }
            setLoadingShowBackdrop(false);
        }
	};

    return(
        <Stack mt={2}>
            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ translate ("Data Customer (CRM)") }</Typography>
            <hr style={{ borderTop: '1px dotted #DADDE1', width:'100%' }} />

            <Stack sx={{ width:'10%' }} mt={2}>
                <Button variant={'contained'} onClick={getCustomerInfo}>
                    { translate('GET') }
                </Button>
            </Stack>

            {dataCust && 
                <Stack direction={'column'} mt={2} spacing={2}>
                    <Grid container>
                        <Grid item xs={4}>
                            <Typography variant={'bodyMedium'}>{ translate('Customer ID') }</Typography>
                        </Grid>
                        <Grid item xs={8}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataCust?.noref}</Typography>
                        </Grid>
                    </Grid>

                    <Grid container>
                        <Grid item xs={4}>
                            <Typography variant={'bodyMedium'}>{ translate('Name') }</Typography>
                        </Grid>
                        <Grid item xs={8}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataCust?.name}</Typography>
                        </Grid>
                    </Grid>

                    <Grid container>
                        <Grid item xs={4}>
                            <Typography variant={'bodyMedium'}>{ translate('NIK') }</Typography>
                        </Grid>
                        <Grid item xs={8}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataCust?.nik}</Typography>
                        </Grid>
                    </Grid>

                    <Grid container>
                        <Grid item xs={4}>
                            <Typography variant={'bodyMedium'}>{ translate('Phone Number') }</Typography>
                        </Grid>
                        <Grid item xs={8}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataCust?.phone_number}</Typography>
                        </Grid>
                    </Grid>

                    <Grid container>
                        <Grid item xs={4}>
                            <Typography variant={'bodyMedium'}>{ translate('District') }</Typography>
                        </Grid>
                        <Grid item xs={8}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataCust?.district}</Typography>
                        </Grid>
                    </Grid>

                    <Grid container>
                        <Grid item xs={4}>
                            <Typography variant={'bodyMedium'}>{ translate('Serial Number') }</Typography>
                        </Grid>
                        <Grid item xs={8}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataCust?.serial_number}</Typography>
                        </Grid>
                    </Grid>
                </Stack>
            }
        </Stack>
    );
}

function ApprovalInformation(parameter : {dataAll:any, setMessages:any}){
    const { translate } = useLocales();
    var dataAll = parameter.dataAll.request;
    var noref = parameter.dataAll.request.nomor_pelanggan;
    const [dataCust, setDataCust] = React.useState<typeCRM | null>(null);
    const { setLoadingShowBackdrop, showSnackbar } = useHelper();
    
    // React.useEffect(() => {
    //     getCustomerInfo();
    // }, [parameter.dataAll])

    const getCustomerInfo = async () => {
        if (noref) {
            setLoadingShowBackdrop(true)
            const response: any = await dummyApi({nomor_pelanggan:noref});
            var responseData = response.data.data;
            if (response.data.code == 0) {
                setDataCust(responseData);
            } else {
                setDataCust(null);
                showSnackbar({message:response.data.info});
            }
            setLoadingShowBackdrop(false);
        }
	};

    return(
        <Stack mt={2}>
            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ dataAll?.status == 2 ? translate ("Approved by") : translate('Rejected by') }</Typography>
            <hr style={{ borderTop: '1px dotted #DADDE1', width:'100%' }} />

            <Stack direction={'column'} mt={2} spacing={2}>

                <Grid container>
                    <Grid item xs={4}>
                        <Typography variant={'bodyMedium'}>{ dataAll?.status == 2 ? translate ("Approved by") : translate('Rejected by') }</Typography>
                    </Grid>
                    <Grid item xs={8}>
                        <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataAll?.updateby_name}</Typography>
                    </Grid>
                </Grid>

                <Grid container>
                    <Grid item xs={4}>
                        <Typography variant={'bodyMedium'}>{ dataAll?.status == 2 ? translate ("Approved date") : translate('Rejected date') }</Typography>
                    </Grid>
                    <Grid item xs={8}>
                        <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {FormatDate(dataAll?.update_dtm)}</Typography>
                    </Grid>
                </Grid>
            </Stack>
        </Stack>
    );
}

const RequesterInformation = ({dataAll, register}:any) => {
    const { translate } = useLocales();
    var dataCustomer = dataAll.request;

    return(
        <Stack mt={2}>
            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ translate ("Customer Account") }</Typography>
            <hr style={{ borderTop: '1px dotted #DADDE1', width:'100%' }} />
            
            <Stack mt={2} direction={'row'} spacing={2}>
                <TextField fullWidth
                    label={ translate('Approval Type') }
                    defaultValue={dataCustomer?.type == 1 ? translate('Unbilled Customer') : translate('Billed Customer') }
                    type="text"
                    InputProps={{
                        readOnly : true
                    }}
                />

                <TextField fullWidth
                    label={ translate('Customer ID') }
                    type="text"
                    InputProps={{
                        readOnly : dataCustomer?.status != 1 ? true : false
                    }}
                    {...register('nomor_pelanggan')}
                />
            </Stack>

            <Stack mt={2} direction={'row'} spacing={2}>
                <TextField fullWidth
                    label={ translate('Customer Name') }
                    type="text"
                    InputProps={{
                        readOnly : dataCustomer?.status != 1 ? true : false
                    }}
                    {...register('name')}
                />

                <TextField fullWidth
                    label={ translate('NIK') }
                    type="text"
                    InputProps={{
                        readOnly : dataCustomer?.status != 1 ? true : false
                    }}
                    {...register('identity')}
                />

                <TextField fullWidth
                    label={ translate('Phone Number') }
                    type="text"
                    InputProps={{
                        readOnly : dataCustomer?.status != 1 ? true : false
                    }}
                    {...register('phone_number')}
                />
            </Stack>

            <Stack mt={2} direction={'row'} spacing={2}>
                <TextField fullWidth
                    label={ translate('District') }
                    type="text"
                    InputProps={{
                        readOnly : dataCustomer?.status != 1 ? true : false
                    }}
                    {...register('district')}
                />

                <TextField fullWidth
                    label={ translate('Serial Number') }
                    type="text"
                    InputProps={{
                        readOnly : dataCustomer?.status != 1 ? true : false
                    }}
                    {...register('serial_number')}
                />
            </Stack>
        </Stack>
    );
}

export const Detail = (props: DialogProps) => {
    const { translate } = useLocales();
	const [isLoading, setLoading] = React.useState(true);
	const [dataAll, setDataAll] = React.useState(null);
    const [dataStatus, setDataStatus] = React.useState('');
    const [openBackDrop, setOpenBackDrop] = React.useState(false);
	const [type, setType] = React.useState(0);
	const [last_payment, setLastPayment] = React.useState('');
    const [message, setMessage] = React.useState('');
    const [openDelete, setOpenDelete] = React.useState(false);
    const [selectedRow, setSelectedRow] = React.useState('');
    const { setLoadingShowBackdrop, showSnackbar } = useHelper();

    React.useEffect(() => {
        setLoading(true);
        reset();
        setMessage('');
        getCustomerInfo();
    },[props.openModal]);

    const getCustomerInfo = async () => {

        if (props.data) {
            const response: any = await detail({request_id:props.data});
            var responseData = response.data.data;
            if (responseData) {
                setDataAll(responseData);
                setDataStatus(responseData.request.status);
                setValue('nomor_pelanggan', responseData.request.nomor_pelanggan);
                setValue('name', responseData.request.name);
                setValue('identity', responseData.request.identity);
                setValue('phone_number', responseData.request.phone_number);
                setValue('district', responseData.request.district);
                setValue('serial_number', responseData.request.serial_number);
                setValue('remark', responseData.request.remark);
                setType(responseData.request.type);
                setLastPayment(responseData.request.last_payment);
            }
            setLoading(false);
        }
	};

    const setMessages = (info:any) => {
        showSnackbar(info);
    }

    const defaultValues = {
        nomor_pelanggan : props.data['nomor_pelanggan'],
        name : props.data['name'],
        identity : props.data['identity'],
        phone_number : props.data['phone_number'],
        district : props.data['district'],
        serial_number : props.data['serial_number'],
        remark : props.data['remark'],
    };

    const { register, handleSubmit, reset, control, getValues, setValue, watch } = useForm({
        defaultValues
    });

    const handleReject = async(e:any) => {
        e.preventDefault();

        try {
            const formValues = getValues();

            if (formValues.nomor_pelanggan == '' || formValues.nomor_pelanggan == null) {
                showSnackbar({message:'customer id cannot be empty please check again'});
                return false;
            }

            if (formValues.name == '' || formValues.name == null) {
                showSnackbar({message:'customer name cannot be empty please check again'});
                return false;
            }

            if (formValues.identity == '' || formValues.identity == null) {
                showSnackbar({message:'customer name cannot be empty please check again'});
                return false;
            }

            if (formValues.phone_number == '' || formValues.phone_number == null) {
                showSnackbar({message:'customer name cannot be empty please check again'});
                return false;
            }

            if (type == 1) {
                if (formValues.district == '' || formValues.phone_number == null) {
                    showSnackbar({message:'customer name cannot be empty please check again'});
                    return false;
                }
    
                if (formValues.phone_number == '' || formValues.phone_number == null) {
                    showSnackbar({message:'phone_number cannot be empty please check again'});
                    return false;
                }
            } else {
                if (formValues.serial_number == '' || formValues.serial_number == null) {
                    showSnackbar({message:'serial_number cannot be empty please check again'});
                    return false;
                }
            }

            if (formValues.remark == '' || formValues.remark == null) {
                showSnackbar({message:'remark cannot be empty please check again'});
                return false;
            }

            setOpenBackDrop(true);
            const response = await update({
                request_id : props.data,
                nomor_pelanggan : formValues.nomor_pelanggan,
                status : '3',
                remark : formValues.remark,
                name : formValues.name,
                identity : formValues.identity,
                type : type,
                phone_number : formValues.phone_number,
                district : formValues.district,
                serial_number : formValues.serial_number,
                last_payment : last_payment
            });

            if (response.data.code == 0) {
                setTimeout(() => {
                    props.closeModal();
                    props.onSubmit();
                    setMessage('');
                }, 2000);
            }
            
            showSnackbar({message:response.data.info});
            setOpenBackDrop(false);
        } catch (error) {
            setOpenBackDrop(false);
        }
    }

    const handleFormSubmit = async(e:any) => {
        e.preventDefault();

        try {
            const formValues = getValues();

            if (formValues.nomor_pelanggan == '' || formValues.nomor_pelanggan == null) {
                showSnackbar({message:'customer id cannot be empty please check again'});
                return false;
            }

            if (formValues.name == '' || formValues.name == null) {
                showSnackbar({message:'customer name cannot be empty please check again'});
                return false;
            }

            if (formValues.remark == '' || formValues.remark == null) {
                showSnackbar({message:'remark cannot be empty please check again'});
                return false;
            }

            if (formValues.identity == '' || formValues.identity == null) {
                showSnackbar({message:'customer name cannot be empty please check again'});
                return false;
            }

            if (formValues.phone_number == '' || formValues.phone_number == null) {
                showSnackbar({message:'customer name cannot be empty please check again'});
                return false;
            }

            if (type == 1) {
                if (formValues.district == '' || formValues.phone_number == null) {
                    showSnackbar({message:'customer name cannot be empty please check again'});
                    return false;
                }
    
                if (formValues.phone_number == '' || formValues.phone_number == null) {
                    showSnackbar({message:'phone_number cannot be empty please check again'});
                    return false;
                }
            } else {
                if (formValues.serial_number == '' || formValues.serial_number == null) {
                    showSnackbar({message:'serial_number cannot be empty please check again'});
                    return false;
                }
            }

            setOpenBackDrop(true);
            const response = await update({
                request_id : props.data,
                nomor_pelanggan : formValues.nomor_pelanggan,
                status : '2',
                remark : formValues.remark,
                name : formValues.name,
                identity : formValues.identity,
                type : type,
                phone_number : formValues.phone_number,
                district : formValues.district,
                serial_number : formValues.serial_number,
                last_payment : last_payment
            });

            if (response.data.code == 0) {
                setTimeout(() => {
                    props.closeModal();
                    props.onSubmit();
                    setMessage('');
                }, 2000);
            }
            
            showSnackbar({message:response.data.info});
            setOpenBackDrop(false);
        } catch (error) {
            setOpenBackDrop(false);
        }
    }

    const handleDelete = () => {
        setSelectedRow(props.data);
        setOpenDelete(true);
    }

    const handleFormDelete = async(e:any) => {
        e.preventDefault();

        try {
            setOpenBackDrop(true);
            const response = await deleteRequest({
                request_id : props.data
            });

            if (response.data.code == 0) {
                setTimeout(() => {
                    props.closeModal();
                    props.onSubmit();
                    setOpenDelete(false);
                    setMessage('');
                }, 2000);
            }
            
            showSnackbar({message:response.data.info});
            setOpenBackDrop(false);
        } catch (error) {
            setOpenBackDrop(false);
        }
    }

    return (
        <div>
            <FormDialog
                handleClose={() => setOpenDelete(false)}
                open={openDelete}
                cancelButtonLabel={ translate ("DISMISS") }
                submitButtonLabel={ translate ("OK") }
                handleSubmit={handleFormDelete}
                title={ translate('Information') }
                maxWidth={'xs'}
                isLoading={isLoading}
            >
                <Box sx={{ width: '100%' }} component="form" noValidate autoComplete="off">
                    <Typography variant={'bodyLarge'}>{ translate('Are you sure to remove this data ') }?</Typography>
                </Box>
                <Backdrop
                    sx={{ color: '#CED8E0', zIndex: (theme) => theme.zIndex.drawer + 1, backgroundColor:'rgba(145, 158, 171, 0.48);' }}
                    open={openBackDrop}
                >
                    <Box sx={{ backgroundColor:'#fff', borderRadius:'12px', p:2 }}>
                        <CircularProgress color="inherit" />
                    </Box>
                </Backdrop>
            </FormDialog>
            <FormDialog
                handleClose={props.closeModal}
                open={props.openModal}
                cancelButtonLabel={ dataStatus != '1' ? translate ("DISMISS") : null }
                rejectButtonLabel={ dataStatus == '1' ? translate ("REJECT") : null }
                submitButtonLabel={ dataStatus == '1' ? translate ("APPROVE") : null }
                deleteButtonLabel={ dataStatus == '1' ? translate ("REMOVE") : null }
                handleSubmit={handleFormSubmit}
                handleReject={handleReject}
                handleDelete={handleDelete}
                title={ translate("Detail") }
                maxWidth={'lg'}
                isLoading={isLoading}
            >
                {!isLoading && 
                <Box sx={{ width: '100%' }} component="form" noValidate autoComplete="off">
                    <Grid container spacing={4}>
                        <Grid item xs={6} sx={{ pl:2 }}>
                            <Stack spacing={3} direction={'column'}>
                                <CustomerInformation dataAll={dataAll}></CustomerInformation>

                                <RequesterInformation dataAll={dataAll} {...{ register }}></RequesterInformation>

                                <Stack mt={2}>
                                    <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ translate ("Approval") }</Typography>
                                    <hr style={{ borderTop: '1px dotted #DADDE1', width:'100%' }} />

                                    <form onSubmit={handleSubmit(handleFormSubmit)}>
                                        <Stack mt={2}>
                                            <Controller
                                                name="remark"
                                                control={control}
                                                defaultValue={defaultValues.remark}
                                                render={({ field }:any) => (
                                                    <FormControl fullWidth>
                                                        <TextField
                                                            label={ translate('Remark') }
                                                            type="text"
                                                            {...field}
                                                            InputProps={{
                                                                readOnly : dataStatus != '1' ? true : false
                                                            }}
                                                        />
                                                    </FormControl>
                                                )}
                                            />
                                        </Stack>
                                    </form>
                                </Stack>
                            </Stack>
                        </Grid>
                        <Grid item xs={6} sx={{ pl:2, mt:-2 }}>
                            {dataStatus == '1' &&
                                <ReporterInformation dataAll={watch()} setMessages={setMessages}></ReporterInformation>
                            }

                            {dataStatus != '1' &&
                                <ApprovalInformation dataAll={dataAll} setMessages={setMessages}></ApprovalInformation>
                            }
                        </Grid>
                    </Grid>
                </Box>
                }
                <Backdrop
                    sx={{ color: '#CED8E0', zIndex: (theme) => theme.zIndex.drawer + 1, backgroundColor:'rgba(145, 158, 171, 0.48);' }}
                    open={openBackDrop}
                >
                    <Box sx={{ backgroundColor:'#fff', borderRadius:'12px', p:2 }}>
                        <CircularProgress color="inherit" />
                    </Box>
                </Backdrop>
            </FormDialog>
        </div>
    )
}