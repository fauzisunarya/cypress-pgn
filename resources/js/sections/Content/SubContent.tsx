import * as React from 'react';
import { Grid, Typography, Button, Link, Box, Stack, TextField } from "@mui/material";
import TextEditor from "@/Components/TextEditor";
import { useLocales } from '@/locales';
import { Controller, useFieldArray } from "react-hook-form";

import dayjs from 'dayjs';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';

interface FilePreviews {
    image?: string;
    image_banner?: string;
}

export default ({ nestIndex, fileSubPreviews, control, setValue, register, getValues }:any) => {
    const { translate } = useLocales();
    const [filePreviews, setFilePreviews] = React.useState<FilePreviews[][]>([]);
    const { fields, remove, append } = useFieldArray({
        control,
        name: `contents.${nestIndex}.body`
    });

    const handleImageChange = (e: any, nIdx: number, num: number, type:any) => {
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = (event: any) => {
            const preview = event.target.result;
            const newFilePreviews: FilePreviews[][] = [...filePreviews];

            if (!newFilePreviews[nIdx]) {
                newFilePreviews[nIdx] = [];
            }

            if (!newFilePreviews[nIdx][num]) {
                newFilePreviews[nIdx][num] = {};
            }

            newFilePreviews[nIdx][num][type] = preview;
            setFilePreviews(newFilePreviews);
            setValue(`contents.${nestIndex}.body.${num}.${type}`, preview);
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    };

    const handleRemoveImage = (nIdx: number, num: number, type:any) => {
        const newFilePreviews: FilePreviews[][] = [...filePreviews];

        if (newFilePreviews[nIdx] && newFilePreviews[nIdx][num]) {
            delete newFilePreviews[nIdx][num][type];
            setFilePreviews(newFilePreviews);
        }
        // Assuming setValue is a function for updating form values
        setValue(`contents.${nestIndex}.body.${num}.${type}`, '');
    };

    React.useEffect(() => {
        if (fileSubPreviews) {
            setFilePreviews(fileSubPreviews);
        }
    }, [fileSubPreviews]);

    return (
        <Box>
            <Stack mt={3}>
                <Grid container>
                    <Grid item xs={3}>
                        <Button variant={'outlined'} onClick={() => append({ detail_id : '', title: '', desc: '', image : '', image_banner : '', url : '', start_date : '', end_date : '' })}>{ translate('Add Sub Content') }</Button>
                    </Grid>
                </Grid>
            </Stack>

            {fields.map((main, num) => (
                <Stack mt={2} key={num}>
                    <Box sx={{
                        border: '1px solid #0088C9;',
                        borderRadius: 1,
                        p:2
                    }}>
                        <Stack>
                            <Grid container>
                                <Grid item xs={6}>
                                    <Box display={'flex'} justifyContent={'left'} alignItems={'right'} sx={{ p:1 }}>
                                        <Typography variant={'h6'}>{ 'SUBCONTENT_'+(num+1) }</Typography>
                                    </Box>
                                </Grid>
                                <Grid item xs={6}>
                                    {num > 0 &&
                                    <Box display={'flex'} justifyContent={'right'} alignItems={'right'} sx={{ p:1 }}>
                                        <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }} onClick={() => remove(num)}>{ translate('Remove Sub Content') }</Link>
                                    </Box>
                                    }
                                </Grid>
                            </Grid>

                            <hr style={{ border:'none', borderTop: '1px solid #0088C9', marginLeft:'-16px', marginRight:'-16px' }} />
                        </Stack>

                        <Stack mt={2} direction={'row'} spacing={2}>
                            <TextField fullWidth
                                label={ translate('Title') }
                                type="text"
                                InputLabelProps={{
                                    shrink: true
                                }}
                                {...register(`contents.${nestIndex}.body.${num}.title`)}
                            />

                            <TextField fullWidth
                                label={ translate('Subtitle') }
                                type="text"
                                InputLabelProps={{
                                    shrink: true
                                }}
                                {...register(`contents.${nestIndex}.body.${num}.subtitle`)}
                            />
                        </Stack>

                        <Stack mt={2}>
                            <TextField fullWidth
                                label={ translate('Url') }
                                type="text"
                                InputLabelProps={{
                                    shrink: true
                                }}
                                {...register(`contents.${nestIndex}.body.${num}.url`)}
                            />
                        </Stack>

                        <Stack mt={2} direction={'row'} spacing={2}>
                            <Controller
                                name={`contents.${nestIndex}.body.${num}.start_date`}
                                control={control}
                                render={({ field }) => (
                                    <LocalizationProvider dateAdapter={AdapterDayjs}>
                                        <div style={{ width: '100%' }}>
                                            <DatePicker
                                                sx={{ width: '100%' }}
                                                label={ translate("Start Date")}
                                                value={dayjs(field.value)}
                                                onChange={(newValue: any) => setValue(`contents.${nestIndex}.body.${num}.start_date`, newValue)}
                                            />
                                        </div>
                                    </LocalizationProvider>
                                )}
                            />

                            <Controller
                                name={`contents.${nestIndex}.body.${num}.end_date`}
                                control={control}
                                render={({ field }) => (
                                    <LocalizationProvider dateAdapter={AdapterDayjs}>
                                        <div style={{ width: '100%' }}>
                                            <DatePicker
                                                sx={{ width: '100%' }}
                                                label={ translate("End Date")}
                                                value={dayjs(field.value)}
                                                onChange={(newValue: any) => setValue(`contents.${nestIndex}.body.${num}.end_date`, newValue)}
                                            />
                                        </div>
                                    </LocalizationProvider>
                                )}
                            />
                        </Stack>

                        <Stack direction={'row'} spacing={2} ml={-2}>
                            <Grid container spacing={2}>
                                <Grid item xs={6}>
                                    <input
                                        accept="image/*"
                                        id={`upload-button.${nestIndex}.body.${num}.image`}
                                        type="file"
                                        style={{ display: 'none' }}
                                        {...register(`contents.${nestIndex}.body.${num}.image`)}
                                        onChange={(e) => handleImageChange(e, nestIndex, num, 'image')}
                                    />
                                    <label htmlFor={`upload-button.${nestIndex}.body.${num}.image`}>
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

                                    {filePreviews[nestIndex] && filePreviews[nestIndex][num]['image'] && (
                                        <Stack mt={1} direction={'column'}>
                                            <Stack direction={'row'} spacing={5}>
                                                <img
                                                    src={filePreviews[nestIndex][num]['image']}
                                                    alt={`File ${num + 1}`}
                                                    style={{ width: '100px', height: '100px', margin: '2px', objectFit: 'cover' }}
                                                />
                                                <Button onClick={() => handleRemoveImage(nestIndex, num, 'image')}>
                                                    {translate('Remove')}
                                                </Button>
                                            </Stack>
                                        </Stack>
                                    )}
                                </Grid>
                                <Grid item xs={6}>
                                    <input
                                        accept="image/*"
                                        id={`upload-button.${nestIndex}.body.${num}.image_banner`}
                                        type="file"
                                        style={{ display: 'none' }}
                                        {...register(`contents.${nestIndex}.body.${num}.image_banner`)}
                                        onChange={(e) => handleImageChange(e, nestIndex, num, 'image_banner')}
                                    />
                                    <label htmlFor={`upload-button.${nestIndex}.body.${num}.image_banner`}>
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
                                                        <Typography variant={'body2'} color={'#919EAB;'} sx={{ fontSize:'1rem' }}>{ translate('Choose image banner') }</Typography>
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

                                    {filePreviews[nestIndex] && filePreviews[nestIndex][num]['image_banner'] && (
                                        <Stack mt={1} direction={'column'}>
                                            <Stack direction={'row'} spacing={5}>
                                                <img
                                                    src={filePreviews[nestIndex][num]['image_banner']}
                                                    alt={`File ${num + 1}`}
                                                    style={{ width: '100px', height: '100px', margin: '2px', objectFit: 'cover' }}
                                                />
                                                <Button onClick={() => handleRemoveImage(nestIndex, num, 'image_banner')}>
                                                    {translate('Remove')}
                                                </Button>
                                            </Stack>
                                        </Stack>
                                    )}
                                </Grid>
                            </Grid>
                        </Stack>

                        <Stack mt={2}>
                            <TextEditor 
                                label={ translate('Sub Content') } 
                                control={control} 
                                defaultValue={getValues(`contents.${nestIndex}.body.${num}.desc`)}
                                {...register(`contents.${nestIndex}.body.${num}.desc`)}
                            />
                        </Stack>
                    </Box>
                </Stack>
            ))}
        </Box>
    );
};
