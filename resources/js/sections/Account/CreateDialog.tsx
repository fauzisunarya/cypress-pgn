import {useEffect, useMemo, useState} from 'react';
import useHelper from '@/hooks/useHelper';
import { useForm } from 'react-hook-form';
import FormDialog from '@/Components/dialog/FormDialog';
import { Autocomplete, Button, Grid, MenuItem, Stack, TextField, Typography, createFilterOptions } from '@mui/material';
import { useLocales } from '@/locales';
import { getListUser } from '@/api_handler/users';
import { addAccount, dummyApi } from '@/api_handler/account';

export interface DialogProps {
    openModal: boolean;
    closeModal: ()=>void;
}

interface typeCRM {
    phone_number?: string;
    name?: string;
    nik?: string;
    district?: string; 
    serial_number?: string;
}

interface typeUser {
    user_id?: number,
    code?: string,
    name?: string,
    email?: string,
    language?: string,
    entity_id?: boolean,
    create_dtm?: string,
    active_dtm?: string,
    terminate_dtm?: string,
    user_status_id?: number,
    auth_type_id?: number,
    auth_dtm?: string,
    nonce?:string,
    token?: string,
    attempts?: number,
    enterprise_id?: string,
    user_status_name?: string,
    member_code?: string
}

export const CreateDialog = (props: DialogProps) => {
    const { translate } = useLocales();

    //dialog create
    const [isLoadingDialog, setIsLoadingDialog] = useState(false);
    const [titleDialog, setTitleDialog] = useState('Add profile');
    const [cancelButtonLabel, setCancelButtonLabel] = useState('Cancel');
    const [submitButtonLabel, setSubmitButtonLabel] = useState('Save');
    const [maxWidth, setMaxWidth] = useState('lg');
    const { setLoadingShowBackdrop, showSnackbar } = useHelper();
    const [selectedUser, setSelectedUser] = useState(null);
    const [inputValue, setInputValue] = useState('');
    const [options, setOptions] = useState([]);
    const [dataUser, setDataUser] = useState<typeUser | null>(null);

    const handleCloseDialog = () => {
        handleReset();
    }

    const handleReset = () => {
        reset();
        setInputValue('');
        setSelectedUser(null);
    }

    const handleSave = async (data: any, event: any) => {

        setLoadingShowBackdrop(true);
        try {

            var userId = null;
            options.map((rowOption : any, index: any) => {
                if((rowOption.name + ' ['+ rowOption.code +']') == data.user_id){
                    userId = rowOption.user_id;
                }
            });

            if (userId) {
                var response: any = await addAccount({
                    user_id :userId,
                    nomor_pelanggan : data.nomor_pelanggan,
                });
                if (response.data.code == 0) {
                    props.closeModal();
                    handleCloseDialog();
                    showSnackbar({
                        message: translate('Success save account')
                    })
                } else {
                    showSnackbar({
                        message: response.data.info
                    })
                }
            }

            setLoadingShowBackdrop(false)
            

        } catch (error:any) {
            showSnackbar({
                message: error.message
            })
            setLoadingShowBackdrop(false)
        }
    };


    const intialValueForm = {
        user_id: null,
        nomor_pelanggan: null,
    }
    
    const { register, handleSubmit, reset, control, getValues, setValue, watch, formState: { errors } } = useForm({
        defaultValues:intialValueForm
    });

    const dialogOption = useMemo(() => {
        return {
            handleClose: props.closeModal,
            open: props.openModal,
            maxWidth: maxWidth,
            isLoading: isLoadingDialog,
            title: titleDialog,
            handleSubmit: handleSubmit(handleSave),
            handleReset: handleCloseDialog,
            cancelButtonLabel: cancelButtonLabel,
            submitButtonLabel: submitButtonLabel
        }
    }, [props.openModal, maxWidth, isLoadingDialog, titleDialog]);

    useEffect(() => {
        // load data user
        loadDataUser();

        options.map((rowOption : any, index: any) => {
            if((rowOption.name + ' ['+ rowOption.code +']') == inputValue){
                setDataUser(rowOption)
            }
        });
    }, [inputValue]);

    useEffect(() => {
        handleReset();
        setDataUser(null);
        setDataCust(null);
    }, [props.openModal]);

    const loadDataUser = async () => {
        const responseListUser: any = await getListUser({
            search: inputValue,
            page: 1,
            length: 10
        });
        if (responseListUser.data.data) {
            var arrData = responseListUser.data.data;
            arrData.map((data : any, index: any) => (
                data.id = data.user_id
            ));
            setOptions(arrData);
        }else{
            setOptions([]);
        }
    }
    
    const [dataCust, setDataCust] = useState<typeCRM | null>(null);

    const getCustomerInfo = async () => {
        var noref = watch('nomor_pelanggan');
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

    return (
        <div>
            <FormDialog {...dialogOption} >
                <Grid container spacing={4}>
                    <Grid item xs={6} sx={{ pl:2 }}>
                        <Stack mt={2}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ translate ("User") }</Typography>
                            <hr style={{ borderTop: '1px dotted #DADDE1', width: '100%' }} />
                            <Autocomplete
                                getOptionLabel={(option) =>
                                typeof option === 'string' ? option : (option.name + ' ['+ option.code +']')
                                }
                                filterOptions={(x) => x}
                                options={options}
                                autoComplete
                                includeInputInList
                                filterSelectedOptions
                                value={selectedUser}
                                noOptionsText="No user found"
                                onChange={(event: any, newValue: any) => {
                                    if(newValue){
                                        setSelectedUser(newValue);
                                    }
                                }}
                                onInputChange={(event, newInputValue) => {
                                    setInputValue(newInputValue);
                                }}
                                renderInput={(params) =>{return (
                                    <TextField
                                        {...params}
                                        required
                                        label="Search User"
                                        placeholder='Search User' 
                                        variant='outlined'
                                        error = {errors.user_id? true : false}
                                        helperText= {errors.user_id? errors.user_id.message : ''}
                                        {...register(
                                            'user_id', 
                                            { 
                                                'required': translate('User cannot be null')
                                            }
                                        )}
                                    />
                                )}}
                            />

                            {dataUser && 
                            <Stack direction={'column'} mt={2} spacing={2}>
                                <Grid container>
                                    <Grid item xs={4}>
                                        <Typography variant={'bodyMedium'}>{ translate('Name') }</Typography>
                                    </Grid>
                                    <Grid item xs={8}>
                                        <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataUser?.name}</Typography>
                                    </Grid>
                                </Grid>

                                <Grid container>
                                    <Grid item xs={4}>
                                        <Typography variant={'bodyMedium'}>{ translate('Phone Number') }</Typography>
                                    </Grid>
                                    <Grid item xs={8}>
                                        <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataUser?.code}</Typography>
                                    </Grid>
                                </Grid>

                                <Grid container>
                                    <Grid item xs={4}>
                                        <Typography variant={'bodyMedium'}>{ translate('Email') }</Typography>
                                    </Grid>
                                    <Grid item xs={8}>
                                        <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataUser?.email}</Typography>
                                    </Grid>
                                </Grid>

                                <Grid container>
                                    <Grid item xs={4}>
                                        <Typography variant={'bodyMedium'}>{ translate('User Id') }</Typography>
                                    </Grid>
                                    <Grid item xs={8}>
                                        <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataUser?.user_id}</Typography>
                                    </Grid>
                                </Grid>
                            </Stack>
                            }
                            
                        </Stack>
                    </Grid>
                    <Grid item xs={6} sx={{ pl:2 }}>
                        <Stack mt={2}>
                            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>{ translate ("Data Customer (CRM)") }</Typography>
                            <hr style={{ borderTop: '1px dotted #DADDE1', width:'100%' }} />

                            <TextField
                                required
                                label="Nomor Pelanggan" 
                                placeholder='Nomor Pelanggan' 
                                variant='outlined'
                                error = {errors.nomor_pelanggan? true : false}
                                helperText= {errors.nomor_pelanggan? errors.nomor_pelanggan.message : ''}
                                {...register(
                                    'nomor_pelanggan', 
                                    { 
                                        'required': translate('Nomor pelanggan cannot be null')
                                    }
                                )}
                                InputProps={{endAdornment: ( 
                                    <Button variant={'contained'} onClick={getCustomerInfo}>
                                        { translate('GET') }
                                    </Button>
                                )}}
                            />   

                            {dataCust && 
                                <Stack direction={'column'} mt={2} spacing={2}>
                                    <Grid container>
                                        <Grid item xs={4}>
                                            <Typography variant={'bodyMedium'}>{ translate('Customer ID') }</Typography>
                                        </Grid>
                                        <Grid item xs={8}>
                                            <Typography variant={'bodyMedium'} sx={{ fontWeight:700 }}>: {dataCust?.phone_number}</Typography>
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
                    </Grid>
                </Grid>
            </FormDialog>
        </div>
    )

}