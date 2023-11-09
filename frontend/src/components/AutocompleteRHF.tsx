import { TextField, CircularProgress, Autocomplete, styled } from "@mui/material";
import { AxiosResponse } from "axios";
import React, { useState, useEffect } from "react";
import { Controller } from "react-hook-form";

type AutocompleteRHFType = {
    control : any;
    name : string;
    setValue : any;
    getValues : any;
    source : (keyword?: any, id ?: any) => Promise<AxiosResponse<any, any>>;
    label : string;
    valueName : string;
    labelName : string;
    defaultValueCustom ?: any;
    isObject : boolean;
}

const TexfieldWithAddorment = styled(TextField)(({ theme }) => ({
    '& .MuiAutocomplete-endAdornment' : {
        top : 'calc(50% - 20px)'
    }
}))

export default function AutocompleteRHF({ control, name,isObject, setValue, getValues, source, label, valueName, labelName, defaultValueCustom }: AutocompleteRHFType) {
    const [open, setOpen] = useState(false);
    const [options, setOptions] = useState<any[]>([]);
    const [inputValue, setInputValue] = useState<string>("");
    const [notfound, setnotfound] = useState(false);
    const [defaultValue, setDefaultValue] = useState(null);
    const loading = open && options.length === 0 && inputValue.length >= 3 && !notfound;

    useEffect(() => {
        let active = true;

        if (!loading) {
            return undefined;
        }

        if (inputValue.length >= 3) {
            (async () => {
                try{
                    const response = await source(inputValue);
                    if (active) {
                        if (response?.data.length > 0) {
                            setnotfound(false);
                            setOptions([...response.data]);
                        } else {
                            setOptions([]);
                            setnotfound(true);
                        }
                    }
                }catch(error){
                    setOptions([]);
                    setnotfound(true);
                }
            })();
        }

        return () => {
            active = false;
        };
    }, [loading, inputValue]);

    useEffect(() => {
        if (!open || (inputValue.length < 3)) {
            setOptions([]);
            setnotfound(false);
        }
    }, [open, inputValue]);

    useEffect(() => {
        (async () => {
            if(!isObject){
                const response = await source(null,getValues(name));
                if(response.data.length > 0){
                    setDefaultValue({...response.data[0]});
                }
            }else{
                if(getValues(name)){
                    setDefaultValue({...getValues(name)})
                }else{
                    setDefaultValue(null)
                }
            }
        })();
    }, [getValues(name)]);

    return (
        <Controller
            control={control}
            name={name}
            defaultValue={defaultValue}
            render={({ field: { onChange, onBlur, value, ref } }) => (
                <Autocomplete
                    value={defaultValue}
                    id="asynchronous-demo"
                    noOptionsText={notfound ? 'No Options' : 'Input 3 or more sentences'}
                    sx={{ width: '100%' }}
                    open={open}
                    onOpen={() => {
                        setOpen(true);
                    }}
                    onClose={() => {
                        setOpen(false);
                    }}
                    onChange={(e, obj) => {
                        if(isObject){
                            setValue(name, obj);
                        }else{
                            setValue(name, obj[valueName]);
                        }
                    }}
                    onInputChange={(event, newInputValue) => {
                        setInputValue(newInputValue);
                    }}
                    isOptionEqualToValue={(option, value) => option[valueName] === value[valueName]}
                    getOptionLabel={(option) => option[labelName]}
                    options={options}
                    loading={loading}
                    renderInput={(params) => (
                        <TexfieldWithAddorment
                            {...params}
                            label={label}
                            InputProps={{
                                ...params.InputProps,
                                endAdornment: (
                                    <React.Fragment>
                                        {loading ? <CircularProgress color="inherit" size={20} /> : null}
                                        {params.InputProps.endAdornment}
                                    </React.Fragment>
                                ),
                            }}
                        />
                    )}
                />
            )}
        />
    );
}