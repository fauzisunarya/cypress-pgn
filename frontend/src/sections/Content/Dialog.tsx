import * as React from 'react';
import { Grid, Typography, Button, Link, DialogTitle, DialogContent, DialogActions, Box, Stack, TextField, FormControl, CircularProgress, Backdrop, Snackbar, InputAdornment, InputLabel, Select, MenuItem } from "@mui/material";
import { styled } from '@mui/material/styles';
import CloseIcon from '@mui/icons-material/Close';
import IconButton from '@mui/material/IconButton';
import { useLocales } from 'src/locales';
import FormDialog from 'src/components/dialog/FormDialog';
import { ChangeEvent, ReactNode } from 'react';
import { SelectChangeEvent } from '@mui/material';
import TextEditor from "src/components/TextEditor";
import Iconify from 'src/components/iconify';
import moment from "moment"
import { any } from 'prop-types';
import { useForm, Controller } from "react-hook-form";
import useHelper from "src/hooks/useHelper";
import { api } from 'src/config';
import { deleteContent, createContent, getContent } from 'src/api_handler/content';

export interface DialogProps {
    openModal: boolean;
    closeModal: ()=>void;
    stateBackdrop: boolean;
    data:any;
    onSubmit:any;
}

type DefaultValues = {
    title: string;
    lang: string;
    content_body: any;
};

export const DeleteDialog = (props: DialogProps) => {
    const { translate } = useLocales();
	const [isLoading, setLoading] = React.useState(true);
    const [message, setMessage] = React.useState('');

    const onSubmitDelete = async () => {
        try {
            const res:any = await deleteContent(props.data);

            if (res.data.code == 0) {
                setMessage(res.data.info);
                setTimeout(() => {
                    props.closeModal();
                    props.onSubmit();
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
    const [selectedFiles, setSelectedFiles] = React.useState([]);
    const [filePreviews, setFilePreviews] = React.useState([]);
    const [defaultValues, setDefaultValues] = React.useState<DefaultValues>({
        title: '',
        lang: '0',
        content_body: '',
    });

    const imageConvert = (selectedFiles:any) => {
        const convertedImagesPromise = selectedFiles.map((image: File) => {
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
        });

        Promise.all(convertedImagesPromise)
        .then((convertedImages) => {
            console.log(convertedImages);
            return convertedImages;
        })
        .catch((error) => {
            console.error(error);
        });
    }

    const contentConvert = (value:any) => {
        const contentBody = {
            value: value,
            summary: '',
            format: 'basic_html',
        };

        return contentBody;
    }

    const handleSubmitCreate = async (value:any, e:any) => {
        e.preventDefault();
        try {
            if (!value.title) {
                setMessage('title is required');
            }

            if (!value.lang) {
                setMessage('language is required');
            }

            // const convertImg = imageConvert(selectedFiles);
            const convertContent = contentConvert(value.content_body);

            const convertedImagesPromise = selectedFiles.map((image: File) => {
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
            });
    
            Promise.all(convertedImagesPromise)
            .then(async(convertedImages) => {
                
                setLoading(true);
                const response:any = await createContent({
                    uuid : props.data,
                    name : value.title,
                    lang : value.lang,
                    content_body : [convertContent],
                    content_image: convertedImages,
                    created_date: moment().toDate(),
                    last_update: moment().toDate(),
                    type_create: props.data ? 'update' : 'create',
                });
    
                setMessage(response.data.info);
                setLoading(false);

                if (response.data.code == 0) {
                    setTimeout(() => {
                        props.closeModal();
                        props.onSubmit();
                    }, 2000);
                } 
            })
            .catch((error) => {
                setLoading(false);
                setMessage(error);
            });
        } catch (error) {
            setLoading(false);
            setMessage(error.message);
        }
    }

    const handleFileChange = (event:any) => {
        const files = event.target.files;
        const maxSizeInBytes = 2 * 1024 * 1024; // 2MB
    
        const selectedFilesArray:any = [];
        const filePreviewsArray:any = [];
    
        for (let i = 0; i < files.length; i++) {
          const file = files[i];
    
            if (file.size > maxSizeInBytes) {
                continue;
            }
    
            selectedFilesArray.push(file);
            const reader = new FileReader();
            reader.onload = () => {
                filePreviewsArray.push(reader.result);
                if (filePreviewsArray.length === files.length) {
                    setFilePreviews(filePreviewsArray);
                }
            };
            reader.readAsDataURL(file);
        }
    
        setSelectedFiles(selectedFilesArray);
    };

    const handleRemoveImage = (index:any) => {
        const updatedPreviews:any = [...filePreviews];
        updatedPreviews.splice(index, 1);
        setFilePreviews(updatedPreviews);
    };

    const { register, handleSubmit, reset, control, getValues, setValue, watch } = useForm({
        defaultValues
    });

    React.useEffect(() => {
        if (defaultValues) {
            reset(defaultValues);
        }
    }, [defaultValues, reset]);

    const getContents = async () => {
        try {
            setLoading(true);

            if (props.data) {
                const response: any = await getContent(props.data);
                var responseData = response.data.data;
                if (responseData) {
                    setDefaultValues({
                        title: responseData.name,
                        lang: responseData.lang,
                        content_body: responseData.content_body,
                    });

                    setFilePreviews(responseData.content_image);
                    setSelectedFiles(responseData.content_image);
                    setTitle(translate('Edit Content'));
                }
            }

            setLoading(false);
        } catch (error) {
            setLoading(false);
            setMessage(error.message);
        }
    }

    React.useEffect(() => {
        reset();
        setValue('title', '');
        setValue('lang', '0');
        setMessage('');
        setFilePreviews([]);
        setSelectedFiles([]);
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
                maxWidth={'xs'}
                isLoading={isLoading}
                message={message}
            >
                <Box sx={{ width: '100%' }} component="form" noValidate autoComplete="off">
                    <Stack>
                        <TextField fullWidth
                            label={ translate('Title') }
                            type="text"
                            InputLabelProps={{
                                shrink: true
                            }}
                            { ...register('title') }
                        />
                    </Stack>

                    <Stack mt={2}>
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

                    <Stack mt={2}>
                        <div>
                            <input
                                accept="image/*"
                                id="upload-button"
                                type="file"
                                multiple // Allow multiple file selection
                                style={{ display: 'none' }}
                                onChange={handleFileChange}
                            />
                            <label htmlFor="upload-button">
                                <span>
                                    <Box sx={{
                                        border: '1px solid #dadfe8;',
                                        borderRadius: 1,
                                        py:2,
                                        pl:2,
                                        cursor: 'pointer',
                                        '&:hover': { border: '1px solid #000;' },
                                    }}>
                                        <Typography variant={'body2'} color={'#919EAB;'} sx={{ fontSize:'1rem' }}>{ translate('Choose file') }</Typography>
                                    </Box>
                                </span>
                            </label>

                            {filePreviews.length > 0 && (
                                <Stack mt={1} direction={'column'}>
                                    {filePreviews.map((preview, index) => (
                                        <Stack direction={'row'} spacing={5} key={index}>
                                            <img
                                                key={index}
                                                src={preview}
                                                alt={`File ${index + 1}`}
                                                style={{ width: '100px', height: '100px', margin: '2px', objectFit:'cover' }}
                                            />
                                            <Button onClick={() => handleRemoveImage(index)}>{ translate('Remove') }</Button>
                                        </Stack>
                                    ))}
                                </Stack>
                            )}
                        </div>
                    </Stack>

                    <Stack mt={2}>
                        <TextEditor 
                            label={ translate('Address') } 
                            control={control} 
                            { ...register('content_body')}
                        />
                    </Stack>
                </Box>
            </FormDialog>
        </div>
    )
}