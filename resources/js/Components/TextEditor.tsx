import React, { useState } from 'react';
import MUIRichTextEditor from 'mui-rte'
import { EditorState, convertFromHTML, ContentState, convertToRaw } from "draft-js";
import { Controller } from 'react-hook-form';
import { ThemeProvider, createTheme } from '@mui/material/styles';
// import { stateToHTML } from 'draft-js-export-html';
import { useLocales } from '@/locales';

const theme = createTheme();

export default function TextEditor({ control, name, defaultValue }: any) {
    return (
        <Controller
            name={name}
            control={control}
            defaultValue={defaultValue}
            render={({ field: { onChange, value } }) => (
                <TextEditorBase value={value} onChange={onChange} defaultValue={defaultValue} />
            )}
        />
    );
}
function TextEditorBase({ value, onChange, defaultValue }: any) {
    const { translate } = useLocales();
    const [editorState, setEditorState] = React.useState(
        EditorState.createEmpty()
    );
    const [content, setContent] = React.useState('');

    React.useEffect(() => {
        if (defaultValue) {
            try {
                const sampleMarkup = '<span>'+defaultValue+'</span>';

                // 1. Convert the HTML
                const contentHTML = convertFromHTML(sampleMarkup)

                // 2. Create the ContentState object
                const state = ContentState.createFromBlockArray(contentHTML.contentBlocks, contentHTML.entityMap)

                // 3. Stringify `state` object from a Draft.Model.Encoding.RawDraftContentState object
                const content = JSON.stringify(convertToRaw(state))
                setContent(content);
            } catch (error) {
                // Handle invalid JSON string, for example, set editor state to empty
                console.error('Invalid JSON string:', defaultValue);
                setContent(content);
            }
        }
    }, [defaultValue]);

    function handleEditorChange(editorState: any) {
        setEditorState(editorState);
        // const rawContentState = convertToRaw(editorState.getCurrentContent());
        // const markup = draftToHtml(rawContentState);
        // onChange(markup)
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
                    defaultValue={content}
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