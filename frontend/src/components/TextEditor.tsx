import { useEffect, useState } from 'react';
import MUIRichTextEditor from 'mui-rte'
import { Controller } from 'react-hook-form';
import { ThemeProvider, createTheme } from '@mui/material/styles';
import { useLocales } from 'src/locales';
import { EditorState, ContentState } from 'draft-js';

const theme = createTheme();

export default function TextEditor({ control, name, defaultValue }: any) {
    return (
        <Controller
            name={name}
            control={control}
            render={({ field: { onChange, value } }) => (
                <TextEditorBase value={value} onChange={onChange}/>
            )}
        />
    );
}
function TextEditorBase({ value, onChange }: any) {
    const { translate } = useLocales();
    const [editorState, setEditorState] = useState(EditorState.createEmpty());

    useEffect(() => {
        if (value) {
            const contentState = ContentState.createFromText(value);
            setEditorState(EditorState.createWithContent(contentState));
        }
    }, [value]);

    function handleEditorChange(content: any) {
        setEditorState(content);
        const plainText = content.getCurrentContent().getPlainText();
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
                    defaultValue={editorState}
                    toolbarButtonSize="small"
                    inlineToolbar
                    label={ translate('Type here') }
                    onChange={handleEditorChange}
                />
            </div>
        </ThemeProvider>
    );
}