import React, { useState } from 'react';
import MUIRichTextEditor from 'mui-rte'
import { EditorState, convertFromHTML, ContentState, convertToRaw } from "draft-js";
import { Controller } from 'react-hook-form';
import { ThemeProvider, createTheme } from '@mui/material/styles';
import { stateToHTML } from 'draft-js-export-html';
import draftToHtml from 'draftjs-to-html';
import { useLocales } from 'src/locales';

const theme = createTheme();

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
    const { translate } = useLocales();
    const [editorState, setEditorState] = React.useState(
        EditorState.createEmpty()
    );

    function handleEditorChange(editorState: any) {
        setEditorState(editorState);
        // const rawContentState = convertToRaw(editorState.getCurrentContent());
        // const markup = draftToHtml(rawContentState);
        // onChange(convertToRaw(editorState.getCurrentContent()));
        const plainText = editorState.getCurrentContent().getPlainText();
        onChange(plainText);
    }

    const editorStyle = {
        border: '1px solid #ccc',
        borderRadius: '5px',
        padding: '8px',
        marginBottom: "8px",
        minHeight: '200px',
    };

    return (
        <ThemeProvider theme={theme}>
            <div style={editorStyle}>
                <MUIRichTextEditor
                    toolbarButtonSize="small"
                    inlineToolbar
                    label={ translate('Type here') }
                    onChange={handleEditorChange}
                    // onChange={ value => stateToHTML(value.getCurrentContent()) }
                />
            </div>
        </ThemeProvider>
    );
}