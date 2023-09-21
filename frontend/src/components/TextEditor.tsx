import React, { useState } from 'react';
import MUIRichTextEditor from 'mui-rte'
import { EditorState, convertToRaw } from "draft-js";
import { Controller } from 'react-hook-form';
import { Paper } from '@mui/material';

export default function TextEditor({ control, name }: any) {
    return (
        <Controller
            name={name}
            control={control}
            defaultValue=""
            render={({ field: { onChange, value } }) => (
                <TextEditorBase value={value} onChange={onChange} />
            )}
        />
    );
}
function TextEditorBase({ value, onChange }: any) {
    const [editorState, setEditorState] = React.useState(
        EditorState.createEmpty()
    );

    // React.useEffect(() => {
    //     if (value) {
    //         const content = JSON.parse(value);
    //         setEditorState(EditorState.createWithContent(content));
    //     }
    // }, [value]);

    function handleEditorChange(editorState: any) {
        setEditorState(editorState);
        onChange(JSON.stringify(editorState.getCurrentContent()));
    }
    return (
        <MUIRichTextEditor
            toolbarButtonSize="small"
            inlineToolbar
            label="Type here..."
            onChange={handleEditorChange}
        />
    );
}