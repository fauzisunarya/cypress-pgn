import React, { useState } from 'react';
import MUIRichTextEditor from 'mui-rte'
import { EditorState, convertToRaw, convertFromRaw  } from "draft-js";
import { Controller } from 'react-hook-form';
import { Paper } from '@mui/material';
import { stateToHTML } from 'draft-js-export-html';

export function convertToHtml(content: any){
    try{
        let html = stateToHTML(convertFromRaw(JSON.parse(content)))
        return html
    }catch(e){
        return content
    }
}
export default function TextEditor({ control, name }: any) {
    return (
        <Controller
            name={name}
            control={control}
            render={({ field: { onChange, value } }) => (
                <TextEditorBase value={value} onChange={onChange} />
            )}
        />
    );
}
function TextEditorBase({ value, onChange }: any) {
    function handleEditorChange(content: any) {
        let raw = convertToRaw(content.getCurrentContent());
        onChange(JSON.stringify(raw));
    }

    return (
        <>
            <MUIRichTextEditor
                defaultValue={value}
                toolbarButtonSize="small"
                inlineToolbar
                label="Type here..."
                onChange={handleEditorChange}
            />
        </>
    );
}