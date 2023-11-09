import * as React from 'react';
import { Grid, Typography, Button, Link, Box, Stack, TextField } from "@mui/material";
import TextEditor from "src/components/TextEditor";
import { useLocales } from 'src/locales';
import { useFieldArray } from "react-hook-form";

export default ({ nestIndex, fileSubPreviews, control, setValue, register }:any) => {
    const { translate } = useLocales();
    const [filePreviews, setFilePreviews] = React.useState([]);
    const { fields, remove, append } = useFieldArray({
        control,
        name: `contents.${nestIndex}.body`
    });

    const handleImageChange = (e: any, nIdx: number, num: number) => {
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = (event: any) => {
            const preview = event.target.result;
            const newFilePreviews:any = [...filePreviews];

            if (!newFilePreviews[nIdx]) {
                newFilePreviews[nIdx] = [];
            }

            newFilePreviews[nIdx][num] = preview;
            setFilePreviews(newFilePreviews);
        };

        if (file) {
            reader.readAsDataURL(file);
        }
        setValue(`contents.${nestIndex}.body.${num}.image`, file);
    };

    const handleRemoveImage = (nIdx: number, num: number) => {
        const newFilePreviews = [...filePreviews];

        if (newFilePreviews[nIdx] && newFilePreviews[nIdx][num]) {
            delete newFilePreviews[nIdx][num];
            setFilePreviews(newFilePreviews);
        }
        setValue(`contents.${nestIndex}.body.${num}.image`, '');
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
                        <Button variant={'outlined'} onClick={() => append({ title: '', desc: '', image : '' })}>{ translate('Add Sub Content') }</Button>
                    </Grid>
                </Grid>
            </Stack>

            {fields.map((main, num) => (
                <Stack mt={2} key={num}>
                    <Box sx={{
                        border: '1px solid #dadfe8;',
                        borderRadius: 1,
                        p:2
                    }}>
                        {num > 0 &&
                        <Stack>
                            <Box display={'flex'} justifyContent={'right'} alignItems={'right'} sx={{ p:1 }}>
                                <Link variant={'body2'} sx={{ color:'#333435', cursor:'pointer' }} onClick={() => remove(num)}>{ translate('Remove Sub Content') }</Link>
                            </Box>

                            <hr style={{ border:'none', borderTop: '1px solid #DADDE1', marginLeft:'-16px', marginRight:'-16px' }} />
                        </Stack>
                        }

                        <Stack mt={2}>
                            <TextField fullWidth
                                label={ translate('Subtitle') }
                                type="text"
                                InputLabelProps={{
                                    shrink: true
                                }}
                                {...register(`contents.${nestIndex}.body.${num}.title`)}
                            />
                        </Stack>

                        <Stack mt={2}>
                            <input
                                accept="image/*"
                                id={`upload-button.${nestIndex}.body.${num}.image`}
                                type="file"
                                style={{ display: 'none' }}
                                {...register(`contents.${nestIndex}.body.${num}.image`)}
                                onChange={(e) => handleImageChange(e, nestIndex, num)}
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

                            {filePreviews[nestIndex] && filePreviews[nestIndex][num] && (
                                <Stack mt={1} direction={'column'}>
                                    <Stack direction={'row'} spacing={5}>
                                        <img
                                            src={filePreviews[nestIndex][num]}
                                            alt={`File ${num + 1}`}
                                            style={{ width: '100px', height: '100px', margin: '2px', objectFit: 'cover' }}
                                        />
                                        <Button onClick={() => handleRemoveImage(nestIndex, num)}>
                                            {translate('Remove')}
                                        </Button>
                                    </Stack>
                                </Stack>
                            )}
                        </Stack>

                        <Stack mt={2}>
                            <TextEditor 
                                label={ translate('Sub Content') } 
                                control={control} 
                                {...register(`contents.${nestIndex}.body.${num}.desc`)}
                            />
                        </Stack>
                    </Box>
                </Stack>
            ))}
        </Box>
    );
};
