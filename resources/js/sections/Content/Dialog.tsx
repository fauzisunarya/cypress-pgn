import * as React from 'react';
import { Grid, Typography, Button, Link, FormHelperText, DialogContent, DialogActions, Box, Stack, TextField, FormControl, CircularProgress, Backdrop, Snackbar, InputAdornment, InputLabel, Select, MenuItem } from "@mui/material";
import IconButton from '@mui/material/IconButton';
import { useLocales } from '@/locales';
import FormDialog from '@/Components/dialog/FormDialog';
import { ChangeEvent, ReactNode } from 'react';
import { SelectChangeEvent } from '@mui/material';
import TextEditor from "@/Components/TextEditor";
import { EditorState, convertFromHTML, ContentState, convertToRaw } from "draft-js";
import Iconify from '@/Components/iconify';
import moment from "moment"
import { useForm, Controller, useFieldArray } from "react-hook-form";
import useHelper from "@/hooks/useHelper";
import { deleteContent, createContent, getContent } from '@/api_handler/content';
import { list } from '@/api_handler/category';
import SubContent from '../Content/SubContent';

import dayjs from 'dayjs';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';

export interface DialogProps {
    openModal: boolean;
    closeModal: ()=>void;
    stateBackdrop: boolean;
    data:any;
    onSubmit:any;
}
interface FilePreviews {
    img_banner?: string;
    img_header?: string;
}
interface ContentType {
    value: {
        id: string;
        image_banner: string;
        url : string,
        start_dtm : Date,
        end_dtm : Date,
        header: {
            title: string;
            subtitle: string;
            image: string;
            desc: string;
        };
        body?: {
            detail_id : any;
            title: string;
            desc: string;
            image: string;
            image_banner : string;
            start_date : Date;
            end_date : Date;
            url :string;
        }[];
    }[];
}

export const DeleteDialog = (props: DialogProps) => {
    const { translate } = useLocales();
	const [isLoading, setLoading] = React.useState(false);
    const { setLoadingShowBackdrop, showSnackbar } = useHelper();
    const [message, setMessage] = React.useState('');

    const onSubmitDelete = async () => {
        try {
            setLoadingShowBackdrop(true);
            const res:any = await deleteContent(props.data);

            if (res.code == 0) {
                showSnackbar({
                    message: res.info
                });
                setTimeout(() => {
                    props.closeModal();
                    props.onSubmit();
                    setLoadingShowBackdrop(false);
                }, 3000);
            } else {
                setLoadingShowBackdrop(false);
                showSnackbar({
                    message: res.info
                });
            }
            
        } catch (error) {
            setMessage('failed');
        }
    }

    return (
        <div>
            <FormDialog
                handleClose={props.closeModal}
                open={props.openModal}
                cancelButtonLabel={ translate ("DISMISS") }
                submitButtonLabel={ translate ("OK") }
                handleSubmit={onSubmitDelete}
                title={ translate("Information") }
                maxWidth={'xs'}
                isLoading={isLoading}
                message={message}
            >
                <Box sx={{ width: '100%' }} component="form" noValidate autoComplete="off">
                    <Typography variant={'body2'}>{ translate('Are you sure to remove this data') } ?</Typography>
                </Box>
            </FormDialog>
        </div>
    )
}

export const CreatedDialog = (props: DialogProps) => {
    const { translate } = useLocales();
    const [titleDialog, setTitle] = React.useState(translate('Create Content'));
	const [isLoading, setLoading] = React.useState(false);
    const [message, setMessage] = React.useState('');
    const [filePreviews, setFilePreviews] = React.useState<FilePreviews[]>([]);
    const [fileSubPreviews, setFileSubPreviews] = React.useState([]);
    const [dataCategory, setCategory] = React.useState([]);
    const { setLoadingShowBackdrop, showSnackbar } = useHelper();

    const defaultValues = {
        uuid:'',
        title: '',
        lang: 'en',
        start_date: new Date(),
        end_date: null,
        category_id: '1',
        contents : [{ 
            id: '', 
            image_banner : '',
            url : '',
            start_dtm : '',
            end_dtm : '',
            header : {
                title: '', 
                subtitle : '',
                image: '',
                desc: '',
            }, 
            body : [{ detail_id : '', title: '', desc: '', image : '', image_banner : '', url : '', start_date : '', end_date : '' }] }],
    };

    const handleSubmitCreate = async (value:any, e:any) => {
        e.preventDefault();
        try {

            setLoadingShowBackdrop(true);

            // Create or update content
            const response:any = await createContent({
                uuid: props.data != '0' ? props.data : '',
                category_id:value.category_id,
                start_date: value.start_date,
                end_date:value.end_date,
                name: value.title,
                lang: value.lang,
                content_body: {
                    value: value.contents,
                    summary: '',
                    format: 'json',
                },
                created_date: moment().format('YYYY-MM-DD HH:mm:ss'),
                last_update: moment().format('YYYY-MM-DD HH:mm:ss'),
                type_create: props.data != '0' ? 'update' : 'create',
            });

            showSnackbar({
                message: response.info
            });
            setLoadingShowBackdrop(false);

            if (response.code == 0) {
                setTimeout(() => {
                    props.closeModal();
                    props.onSubmit();
                }, 2000);
            }
        } catch (error) {
            setLoadingShowBackdrop(false);
            showSnackbar({
                message: translate('failed')
            });
        }
    }

    const { register, handleSubmit, reset, control, getValues, setValue, watch, formState: { errors } } = useForm({
        defaultValues
    });

    const { fields, append, remove } = useFieldArray({
        control,
        name: 'contents',
    }); 
    
    const handleImageChange = (e:any, index:any, type:any) => {
        const file = e.target.files[0];
        const reader = new FileReader();
    
        reader.onload = (event:any) => {
            const preview = event.target.result;
            const newFilePreviews:any = [...filePreviews];
    
            if (!newFilePreviews[index]) {
                newFilePreviews[index] = {};
            }
    
            newFilePreviews[index][type] = preview;
            setFilePreviews(newFilePreviews);

            if (type == 'img_banner') {
                setValue(`contents.${index}.image_banner`, preview);
            } else {
                setValue(`contents.${index}.header.image`, preview);
            }
        };
    
        if (file) {
            reader.readAsDataURL(file);
        }
    };    

    const handleRemoveImage = (index:any, type:any) => {
        const newData = filePreviews.map((item:any, idx:any) => {
            if (idx === index) {
                delete item[type];
            }
            return item;
        });
    
        const filteredData = newData.filter((item:any) => Object.keys(item).length > 0);

        setFilePreviews(filteredData);
        if (type == 'img_banner') {
            setValue(`contents.${index}.image_banner`, '');
        } else {
            setValue(`contents.${index}.header.image`, '');
        }
    };

    const getContents = async () => {
        try {
            setLoadingShowBackdrop(true);
            reset(defaultValues);
            setMessage('');
            setFilePreviews([]);
            setFileSubPreviews([]);

            if (props.data != '0') {
                const response: any = await getContent(props.data);
                var responseData = response.data[0];
                if (responseData) {
                    setValue('uuid', responseData.id);
                    setValue('title', responseData.name);
                    setValue('lang', responseData.language);
                    setValue('category_id', responseData.category_id);
                    setValue('start_date', responseData.start_date);
                    setValue('end_date', responseData.end_date);
                    setValue('contents', responseData.content_body.value);
                    setTitle(translate('Edit Content'));
                    editContent(responseData.content_body);
                }
            }

            setLoadingShowBackdrop(false);
        } catch (error) {
            setLoadingShowBackdrop(false);
            // setMessage('failed');
        }
    }

    const getCategory = async (key:any) => {
        try {

            const response: any = await list(key);
            var responseData = response.data;
            if (responseData) {
                setCategory(responseData.data);
            }

        } catch (error) {
            showSnackbar({
                message: translate('failed get category')
            });
        }
    }

    const editContent = (content:ContentType) => {
        const contents = content.value;
        const newFilePreviews:any = [...filePreviews];
        const newFileSubPreviews:any = [...fileSubPreviews];
        const urlRegex = /^(ftp|http|https):\/\/[^ "]+$/;
        contents.forEach((value, index) => {
            if (value.image_banner && value.image_banner != '' && urlRegex.test(value.image_banner)) {
                const type = 'img_banner';
        
                if (!newFilePreviews[index]) {
                    newFilePreviews[index] = {};
                }
        
                newFilePreviews[index][type] = value.image_banner;
            }

            if (value.header.image && value.header.image != '' && urlRegex.test(value.header.image)) {
                const type = 'img_header';
        
                if (!newFilePreviews[index]) {
                    newFilePreviews[index] = {};
                }
                
                newFilePreviews[index][type] = value.header.image;
            } 
        
            if (value.body) {
                (value.body).forEach((sub, idx) => {
                    if (!newFileSubPreviews[index]) {
                        newFileSubPreviews[index] = {};
                    }

                    if (sub.image && sub.image != '' && urlRegex.test(sub.image)) {
                
                        if (!newFileSubPreviews[index][idx]) {
                            newFileSubPreviews[index][idx] = {};
                        }
                
                        newFileSubPreviews[index][idx]['image'] = sub.image;
                    }

                    if (sub.image_banner && sub.image_banner != '' && urlRegex.test(sub.image_banner)) {
                
                        if (!newFileSubPreviews[index][idx]) {
                            newFileSubPreviews[index][idx] = {};
                        }
                
                        newFileSubPreviews[index][idx]['image_banner'] = sub.image_banner;
                    }
                });
            }
        });

        setFilePreviews(newFilePreviews);
        setFileSubPreviews(newFileSubPreviews);
    }

    React.useEffect(() => {
        getCategory("");
        reset(defaultValues);
        setMessage('');
        setFilePreviews([]);
        setFileSubPreviews([]);
        setTitle(translate('Create Content'));
        getContents();
    },[props.data]);

    return (
        <div>
            <FormDialog
                handleClose={props.closeModal}
                open={props.openModal}
                cancelButtonLabel={ translate ("DISMISS") }
                submitButtonLabel={ translate ("SAVE CHANGE") }
                handleSubmit={handleSubmit(handleSubmitCreate)}
                reset={reset}
                title={titleDialog}
                maxWidth={'md'}
                isLoading={isLoading}
                message={message}
            >
                <Box sx={{ width: '100%' }} component="form" noValidate autoComplete="off">
                    <Stack direction={'row'} spacing={2}>
                        {props.data != '0' &&
                        <TextField fullWidth
                            label={ translate('ID') }
                            type="text"
                            InputLabelProps={{
                                shrink: true
                            }}
                            InputProps={{
                                readOnly: true
                            }}
                            { ...register('uuid') }
                        />
                        }

                        <TextField fullWidth
                            label={ translate('Name') }
                            type="text"
                            InputLabelProps={{
                                shrink: watch('title') ? true : false
                            }}
                            required
                            error = {errors.title? true : false}
                            helperText= {errors.title? errors.title.message : ''}
                            {...register('title', { required: translate('Name cannot be empty') })}
                        />

                        <FormControl fullWidth>
                            <InputLabel htmlFor="select">{ translate('Language *') }</InputLabel>
                            <Controller
                                name="lang"
                                control={control}
                                defaultValue={defaultValues.lang}
                                render={({ field, fieldState }) => (
                                    <>
                                        <Select {...field} required label={ translate('Language *') }>
                                            <MenuItem value="id">{ translate ("Bahasa") }</MenuItem>
                                            <MenuItem value="en">{ translate ("English") }</MenuItem>
                                        </Select>
                                        <FormHelperText>{fieldState?.error?.message}</FormHelperText>
                                    </>
                                )}
                                rules={{ required: translate('Language cannot be empty') }}
                            />
                        </FormControl>
                    </Stack>

                    <Stack mt={2}>
                        <FormControl fullWidth>
                            <InputLabel htmlFor="select">{ translate('Category *') }</InputLabel>
                            <Controller
                                name="category_id"
                                control={control}
                                defaultValue={defaultValues.category_id}
                                render={({ field, fieldState }) => (
                                    <>
                                        <Select {...field} required label={ translate('Category *') }>
                                            {dataCategory.map((row:any) => (
                                                <MenuItem key={row.id} value={row.id}>{ row.category_name }</MenuItem>
                                            ))}
                                        </Select>
                                        <FormHelperText>{fieldState?.error?.message}</FormHelperText>
                                    </>
                                )}
                                rules={{ required: translate('Category cannot be empty') }}
                            />
                        </FormControl>
                    </Stack>

                    <Stack mt={2} direction={'row'} spacing={2}>
                        <Controller
                            name="start_date"
                            control={control}
                            render={({ field }) => (
                                <LocalizationProvider dateAdapter={AdapterDayjs}>
                                    <div style={{ width: '100%' }}>
                                        <DatePicker
                                            sx={{ width: '100%' }}
                                            label={ translate("Start Date *")}
                                            value={dayjs(field.value)}
                                            onChange={(newValue: any) => setValue('start_date', newValue)}
                                        />
                                    </div>
                                </LocalizationProvider>
                            )}
                        />

                        <Controller
                            name="end_date"
                            control={control}
                            render={({ field }) => (
                                <LocalizationProvider dateAdapter={AdapterDayjs}>
                                    <div style={{ width: '100%' }}>
                                        <DatePicker
                                            sx={{ width: '100%' }}
                                            label={ translate("End Date")}
                                            value={dayjs(field.value)}
                                            onChange={(newValue: any) => setValue('end_date', newValue)}
                                        />
                                    </div>
                                </LocalizationProvider>
                            )}
                        />
                    </Stack>

                    <Stack mt={3}>
                        <Grid container>
                            <Grid item xs={3}>
                                <Button variant={'outlined'} onClick={() => append({ id: '', image_banner : '', start_dtm : '', end_dtm : '', url : '', header: { title:'', subtitle: '', image:'', desc:'' }, body : [{ detail_id : '', title: '', desc: '', image : '', image_banner : '', url : '', start_date : '', end_date : '' }] })}>{ translate('Add Content') }</Button>
                            </Grid>
                        </Grid>
                    </Stack>

                    {fields.map((main, index) => (
                        <Stack key={main.id} mt={2}>
                            <Box sx={{
                                border: '1px solid #11D78F;',
                                borderRadius: 1,
                                px:2,
                                pb:2
                            }}>
                                <Stack>
                                    <Grid container>
                                        <Grid item xs={6}>
                                            <Box display={'flex'} justifyContent={'left'} alignItems={'right'} sx={{ p:1 }}>
                                                <Typography variant={'h6'}>{ 'CONTENT_'+(index+1) }</Typography>
                                            </Box>
                                        </Grid>
                                        <Grid item xs={6}>
                                            {index > 0 &&
                                            <Box display={'flex'} justifyContent={'right'} alignItems={'right'} sx={{ p:1 }}>
                                                <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }} onClick={() => remove(index)}>{ translate('Remove Content') }</Link>
                                            </Box>
                                            }
                                        </Grid>
                                    </Grid>

                                    <hr style={{ border:'none', borderTop: '1px solid #11D78F', marginLeft:'-16px', marginRight:'-16px' }} />
                                </Stack>

                                <Stack direction={'row'} spacing={2} mt={2}>
                                    <TextField
                                        fullWidth
                                        label={translate('Title')}
                                        type="text"
                                        InputLabelProps={{
                                            shrink: watch(`contents.${index}.header.title`) ? true : false,
                                        }}
                                        required
                                        error={errors.contents?.[index]?.header?.title ? true : false}
                                        helperText={errors.contents?.[index]?.header?.title ? errors.contents?.[index]?.header?.title?.message : ''}
                                        {...register(`contents.${index}.header.title`, { required : translate('Title cannot be empty') })}
                                    />

                                    <TextField fullWidth
                                        label={ translate('Subtitle') }
                                        type="text"
                                        InputLabelProps={{
                                            shrink: watch(`contents.${index}.header.subtitle`) ? true : false,
                                        }}
                                        required
                                        error={errors.contents?.[index]?.header?.subtitle ? true : false}
                                        helperText={errors.contents?.[index]?.header?.subtitle ? errors.contents?.[index]?.header?.subtitle?.message : ''}
                                        {...register(`contents.${index}.header.subtitle`, { required : translate('Subtitle cannot be empty') })}
                                    />
                                </Stack>

                                <Stack mt={2}>
                                    <TextField fullWidth
                                        label={ translate('Url') }
                                        type="text"
                                        InputLabelProps={{
                                            shrink: watch(`contents.${index}.url`) ? true : false,
                                        }}
                                        error={errors.contents?.[index]?.url ? true : false}
                                        helperText={errors.contents?.[index]?.url ? errors.contents?.[index]?.url?.message : ''}
                                        {...register(`contents.${index}.url`, { pattern: {
                                            value: /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/,
                                            message: translate('Please insert a valid link')
                                        } })}
                                    />
                                </Stack>

                                <Stack mt={2} direction={'row'} spacing={2}>
                                    <Controller
                                        name={`contents.${index}.start_dtm`}
                                        control={control}
                                        render={({ field }) => (
                                            <LocalizationProvider dateAdapter={AdapterDayjs}>
                                                <div style={{ width: '100%' }}>
                                                    <DatePicker
                                                        sx={{ width: '100%' }}
                                                        label={ translate("Start Date")}
                                                        value={dayjs(field.value)}
                                                        onChange={(newValue: any) => setValue(`contents.${index}.start_dtm`, newValue)}
                                                    />
                                                </div>
                                            </LocalizationProvider>
                                        )}
                                    />

                                    <Controller
                                        name={`contents.${index}.end_dtm`}
                                        control={control}
                                        render={({ field }) => (
                                            <LocalizationProvider dateAdapter={AdapterDayjs}>
                                                <div style={{ width: '100%' }}>
                                                    <DatePicker
                                                        sx={{ width: '100%' }}
                                                        label={ translate("End Date")}
                                                        value={dayjs(field.value)}
                                                        onChange={(newValue: any) => setValue(`contents.${index}.end_dtm`, newValue)}
                                                    />
                                                </div>
                                            </LocalizationProvider>
                                        )}
                                    />
                                </Stack>

                                <Stack direction={'row'} spacing={2} ml={-2}>
                                    <Grid container spacing={2}>
                                        <Grid item xs={6}>
                                            <Stack>
                                                <input
                                                    accept="image/*"
                                                    id={`upload-button-banner-${index}`}
                                                    type="file"
                                                    style={{ display: 'none' }}
                                                    {...register(`contents.${index}.image_banner`)}
                                                    onChange={(e) => handleImageChange(e, index, 'img_banner')}
                                                />
                                                <label htmlFor={`upload-button-banner-${index}`}>
                                                    <span>
                                                        <Box sx={{
                                                            border: '1px solid #dadfe8;',
                                                            borderRadius: 1,
                                                            py:1.8,
                                                            pl:2,
                                                            cursor: 'pointer',
                                                            '&:hover': { border: '1px solid #000;' },
                                                        }}>
                                                            <Typography variant={'body2'} color={'#919EAB;'} sx={{ fontSize:'1rem' }}>{ translate('Choose image banner') }</Typography>
                                                            {/* <Grid container>
                                                                <Grid item xs={6}>
                                                                    <Typography variant={'body2'} color={'#919EAB;'} sx={{ fontSize:'1rem' }}>{ translate('Choose image banner') }</Typography>
                                                                </Grid>
                                                                <Grid item xs={6}>
                                                                    <Box display={'flex'} justifyContent={'right'} alignContent={'right'} sx={{ my:-2 }}>
                                                                        <Button variant={'soft'} size={'large'} sx={{ p:3.5 }}>{ translate('UPLOAD') }</Button>
                                                                    </Box>
                                                                </Grid>
                                                            </Grid> */}
                                                        </Box>
                                                    </span>
                                                </label>

                                                {filePreviews[index] && filePreviews[index]?.img_banner && (
                                                    <Stack mt={1} direction={'column'}>
                                                        <Stack direction={'row'} spacing={5} key={index}>
                                                            <img
                                                                src={filePreviews[index]?.img_banner}
                                                                alt={`File ${index + 1}`}
                                                                style={{ width: '100px', height: '100px', margin: '2px', objectFit: 'cover' }}
                                                            />
                                                            <Button onClick={() => handleRemoveImage(index, 'img_banner')}>{ translate('Remove') }</Button>
                                                        </Stack>
                                                    </Stack>
                                                )}
                                            </Stack>
                                        </Grid>
                                        <Grid item xs={6}>
                                            <Stack>
                                                <input
                                                    accept="image/*"
                                                    id={`upload-button-header-${index}`}
                                                    type="file"
                                                    style={{ display: 'none' }}
                                                    {...register(`contents.${index}.header.image`)}
                                                    onChange={(e) => handleImageChange(e, index, 'img_header')}
                                                />
                                                <label htmlFor={`upload-button-header-${index}`}>
                                                    <span>
                                                        <Box sx={{
                                                            border: '1px solid #dadfe8;',
                                                            borderRadius: 1,
                                                            py:1.8,
                                                            pl:2,
                                                            cursor: 'pointer',
                                                            '&:hover': { border: '1px solid #000;' },
                                                        }}>
                                                            {/* <Grid container>
                                                                <Grid item xs={6}> */}
                                                                    <Typography variant={'body2'} color={'#919EAB;'} sx={{ fontSize:'1rem' }}>{ translate('Choose image header') }</Typography>
                                                                {/* </Grid>
                                                                <Grid item xs={6}>
                                                                    <Box display={'flex'} justifyContent={'right'} alignContent={'right'} sx={{ my:-2 }}>
                                                                        <Button variant={'soft'} size={'large'} sx={{ p:3.5 }}>{ translate('UPLOAD') }</Button>
                                                                    </Box>
                                                                </Grid>
                                                            </Grid> */}
                                                        </Box>
                                                    </span>
                                                </label>

                                                {filePreviews[index] && filePreviews[index]?.img_header && (
                                                    <Stack mt={1} direction={'column'}>
                                                        <Stack direction={'row'} spacing={5} key={index}>
                                                            <img
                                                                src={filePreviews[index]?.img_header}
                                                                alt={`File ${index + 1}`}
                                                                style={{ width: '100px', height: '100px', margin: '2px', objectFit: 'cover' }}
                                                            />
                                                            <Button onClick={() => handleRemoveImage(index, 'img_header')}>{ translate('Remove') }</Button>
                                                        </Stack>
                                                    </Stack>
                                                )}
                                            </Stack>
                                        </Grid>
                                    </Grid>
                                </Stack>

                                <Stack mt={2}>
                                    <TextEditor 
                                        label={ translate('Main Content') } 
                                        control={control} 
                                        defaultValue={getValues(`contents.${index}.header.desc`)}
                                        {...register(`contents.${index}.header.desc`)}
                                    />
                                </Stack>

                                <SubContent nestIndex={index} fileSubPreviews={fileSubPreviews} {...{ control, setValue, register, getValues, watch, formState: { errors } }} />
                            </Box>
                        </Stack>
                    ))}
                </Box>
            </FormDialog>
        </div>
    )
}