import * as React from 'react';
import { Grid, Typography, Button, Link, DialogTitle, DialogContent, DialogActions, Box, Stack, TextField, FormControl, CircularProgress, Backdrop, Snackbar, InputAdornment, InputLabel, Select, MenuItem } from "@mui/material";
import { styled } from '@mui/material/styles';
import CloseIcon from '@mui/icons-material/Close';
import IconButton from '@mui/material/IconButton';
import { useLocales } from 'src/locales';
import FormDialog from 'src/Components/dialog/FormDialog';
import { ChangeEvent, ReactNode } from 'react';
import { SelectChangeEvent } from '@mui/material';
import TextEditor from "src/Components/TextEditor";
import MUIRichTextEditor from 'mui-rte'
import { EditorState, convertFromHTML, ContentState, convertToRaw } from "draft-js";
import Iconify from 'src/Components/iconify';
import moment from "moment"
import { any } from 'prop-types';
import { useForm, Controller, useFieldArray } from "react-hook-form";
import useHelper from "src/hooks/useHelper";
import { api } from 'src/config';
import { deleteContent, createContent, getContent } from 'src/api_handler/content';
import SubContent from '../Content/SubContent';

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
        header: {
            title: string;
            image: string;
            desc: string;
        };
        img_banner: string;
        body?: {
            title: string;
            desc: string;
            image: string;
        }[];
    }[];
}

export const DeleteDialog = (props: DialogProps) => {
    const { translate } = useLocales();
	const [isLoading, setLoading] = React.useState(true);
    const [message, setMessage] = React.useState('');

    const onSubmitDelete = async () => {
        try {
            setLoading(true);
            const res:any = await deleteContent(props.data);

            if (res.code == 0) {
                setMessage(res.info);
                setTimeout(() => {
                    props.closeModal();
                    props.onSubmit();
                    setLoading(false);
                }, 3000);
            }
        } catch (error) {
            setMessage(error.message);
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
	const [isLoading, setLoading] = React.useState(true);
    const [message, setMessage] = React.useState('');
    const [filePreviews, setFilePreviews] = React.useState<FilePreviews[]>([]);
    const [fileSubPreviews, setFileSubPreviews] = React.useState([]);

    const defaultValues = {
        title: '',
        lang: '0',
        contents : [{ 
            id: '', 
            header : {
                title: '', 
                image: '',
                desc: '',
            },
            img_banner : '', 
            body : [{ title: '', desc: '', image : '' }] }],
    };

    const contentConvert = (value:any) => {
        const contentBody = value;
        const dataImage: File[] = [];
        const urlRegex = /^(ftp|http|https):\/\/[^ "]+$/;

        contentBody.forEach((content:any) => {
            if (content.img_banner != '' && !urlRegex.test(content.img_banner)) {
                dataImage.push(content.img_banner);
                content.img_banner = content.img_banner.name;
            }

            if (content.header.image != '' && !urlRegex.test(content.header.image)) {
                dataImage.push(content.header.image);
                content.header.image = content.header.image.name;
            }

            content.header = {
                image : content.header.image,
                title : content.header.title,
                desc : content.header.desc
            };

            if (content.body) {
                (content.body).forEach((sub:any) => {
                    if (sub.image != '' && !urlRegex.test(sub.image)) {
                        dataImage.push(sub.image);
                        sub.image = sub.image.name;
                    }
                });
            }
        });

        return {
            content : contentBody,
            image : dataImage
        }
    }

    const handleSubmitCreate = async (value:any, e:any) => {
        e.preventDefault();
        try {
            if (!value.title) {
                setMessage('name is required');
            }

            if (!value.lang) {
                setMessage('language is required');
            }

            const convertContent = contentConvert(value.contents);
            const fileImage = convertContent.image;

            const convertImage = (image: File) => {
                return new Promise<{ filename: string, mimeType: string, file: string | ArrayBuffer | null }>((resolve) => {
                    const reader = new FileReader();
            
                    reader.onload = () => {
                        const fileImage = reader.result;
                        resolve({
                            filename: image.name,
                            mimeType: image.type,
                            file: fileImage,
                        });
                    };
            
                    reader.readAsDataURL(image);
                });
            };

            // Convert images to base64
            const convertedImages = await Promise.all(fileImage.map(convertImage));

            setLoading(true);

            // Create or update content
            const response:any = await createContent({
                uuid: props.data != '0' ? props.data : '',
                name: value.title,
                lang: value.lang,
                content_body: [{
                    value: convertContent.content,
                    summary: '',
                    format: 'json',
                }],
                content_image: convertedImages,
                created_date: moment().format('YYYY-MM-DD HH:mm:ss'),
                last_update: moment().format('YYYY-MM-DD HH:mm:ss'),
                type_create: props.data != '0' ? 'update' : 'create',
            });

            setMessage(response.info);
            setLoading(false);

            if (response.code == 0) {
                setTimeout(() => {
                    props.closeModal();
                    props.onSubmit();
                }, 2000);
            }
        } catch (error) {
            setLoading(false);
            setMessage(error.message);
        }
    }

    const { register, handleSubmit, reset, control, getValues, setValue, watch } = useForm({
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
        };
    
        if (file) {
            reader.readAsDataURL(file);
        }

        if (type == 'img_banner') {
            setValue(`contents.${index}.img_banner`, file);
        } else {
            setValue(`contents.${index}.header.image`, file);
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
            setValue(`contents.${index}.img_banner`, '');
        } else {
            setValue(`contents.${index}.header.image`, '');
        }
    };

    const getContents = async () => {
        try {
            setLoading(true);
            reset(defaultValues);
            setMessage('');
            setFilePreviews([]);
            setFileSubPreviews([]);

            if (props.data != '0') {
                const response: any = await getContent(props.data);
                var responseData = response.data;
                if (responseData) {
                    setValue('title', responseData.name);
                    setValue('lang', responseData.lang);
                    setValue('contents', responseData.content_body.value);
                    editContent(responseData.content_body);

                    setTitle(translate('Edit Content'));
                }
            }

            setLoading(false);
        } catch (error) {
            setLoading(false);
            setMessage(error.message);
        }
    }

    const editContent = (content:ContentType) => {
        const contents = content.value;
        const newFilePreviews:any = [...filePreviews];
        const newFileSubPreviews:any = [...fileSubPreviews];
        contents.forEach((value, index) => {
            if (value.img_banner) {
                const type = 'img_banner';
        
                if (!newFilePreviews[index]) {
                    newFilePreviews[index] = {};
                }
        
                newFilePreviews[index][type] = value.img_banner;
            }

            if (value.header.image) {
                const type = 'img_header';
        
                if (!newFilePreviews[index]) {
                    newFilePreviews[index] = {};
                }
                
                newFilePreviews[index][type] = value.header.image;
            } 
        
            if (value.body) {
                (value.body).forEach((sub, idx) => {
                    if (sub.image) {
                
                        if (!newFileSubPreviews[index]) {
                            newFileSubPreviews[index] = {};
                        }
                
                        newFileSubPreviews[index][idx] = sub.image;
                    }
                });
            }
        });

        setFilePreviews(newFilePreviews);
        setFileSubPreviews(newFileSubPreviews);
    }

    React.useEffect(() => {
        reset(defaultValues);
        setMessage('');
        setFilePreviews([]);
        setFileSubPreviews([]);
        setTitle(translate('Create Content'));
        getContents();
    },[props.data]);

    console.log(fileSubPreviews);
    console.log(filePreviews);

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
                        <TextField fullWidth
                            label={ translate('Name') }
                            type="text"
                            InputLabelProps={{
                                shrink: true
                            }}
                            { ...register('title') }
                        />

                        <Controller
                            name="lang"
                            control={control}
                            defaultValue={defaultValues.lang}
                            render={({ field }) => (
                                <FormControl fullWidth>
                                    <InputLabel id="demo-simple-select-label"></InputLabel>
                                    <Select
                                        labelId="demo-simple-select-label"
                                        id="demo-simple-select"
                                        {...field}
                                        onChange={(event: SelectChangeEvent<string>, child: ReactNode) => {
                                            field.onChange(event.target.value)
                                        }}
                                    >
                                        <MenuItem value="0">{ translate ("Choose Language") }</MenuItem>
                                        <MenuItem value="id">{ translate ("Bahasa") }</MenuItem>
                                        <MenuItem value="en">{ translate ("English") }</MenuItem>
                                    </Select>
                                </FormControl>
                            )}
                        />
                    </Stack>

                    <Stack mt={3}>
                        <Grid container>
                            <Grid item xs={3}>
                                <Button variant={'outlined'} onClick={() => append({ id: '', img_banner : '', header: { title:'', image:'', desc:'' }, body : [{ title: '', desc: '', image : '' }] })}>{ translate('Add Content') }</Button>
                            </Grid>
                        </Grid>
                    </Stack>

                    {fields.map((main, index) => (
                        <Stack key={main.id} mt={2}>
                            <Box sx={{
                                border: '1px solid #dadfe8;',
                                borderRadius: 1,
                                px:2,
                                pb:2
                            }}>
                                {index > 0 &&
                                <Stack>
                                    <Box display={'flex'} justifyContent={'right'} alignItems={'right'} sx={{ p:1 }}>
                                        <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }} onClick={() => remove(index)}>{ translate('Remove Content') }</Link>
                                    </Box>

                                    <hr style={{ border:'none', borderTop: '1px solid #DADDE1', marginLeft:'-16px', marginRight:'-16px' }} />
                                </Stack>
                                }

                                <Stack direction={'row'} spacing={2} mt={2}>
                                    <TextField fullWidth
                                        label={ translate('ID') }
                                        type="text"
                                        InputLabelProps={{
                                            shrink: true,
                                        }}
                                        {...register(`contents.${index}.id`)}
                                    />

                                    <TextField fullWidth
                                        label={ translate('Title') }
                                        type="text"
                                        InputLabelProps={{
                                            shrink: true
                                        }}
                                        {...register(`contents.${index}.header.title`)}
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
                                                    {...register(`contents.${index}.img_banner`)}
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
                                                            <Grid container>
                                                                <Grid item xs={6}>
                                                                    <Typography variant={'body2'} color={'#919EAB;'} sx={{ fontSize:'1rem' }}>{ translate('Choose image banner') }</Typography>
                                                                </Grid>
                                                                <Grid item xs={6}>
                                                                    <Box display={'flex'} justifyContent={'right'} alignContent={'right'} sx={{ my:-2 }}>
                                                                        <Button variant={'soft'} size={'large'} sx={{ p:3.5 }}>{ translate('UPLOAD') }</Button>
                                                                    </Box>
                                                                </Grid>
                                                            </Grid>
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
                                                            <Grid container>
                                                                <Grid item xs={6}>
                                                                    <Typography variant={'body2'} color={'#919EAB;'} sx={{ fontSize:'1rem' }}>{ translate('Choose image header') }</Typography>
                                                                </Grid>
                                                                <Grid item xs={6}>
                                                                    <Box display={'flex'} justifyContent={'right'} alignContent={'right'} sx={{ my:-2 }}>
                                                                        <Button variant={'soft'} size={'large'} sx={{ p:3.5 }}>{ translate('UPLOAD') }</Button>
                                                                    </Box>
                                                                </Grid>
                                                            </Grid>
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

                                <SubContent nestIndex={index} fileSubPreviews={fileSubPreviews} {...{ control, setValue, register, getValues }} />
                            </Box>
                        </Stack>
                    ))}
                </Box>
            </FormDialog>
        </div>
    )
}