import {
    Edit,
    SimpleForm,
    TextInput,
    BooleanInput,
    DateInput,
    ImageInput,
    ImageField,
    FunctionField,
    useGetIdentity,
    useRecordContext,
    Toolbar,
    SaveButton,
    useRedirect,
    useNotify,
    useRefresh,
} from 'react-admin';
import { Box, Typography, Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const CustomEditToolbar = () => {
    const redirect = useRedirect();

    return (
        <Toolbar>
            <Button
                startIcon={<ArrowBackIcon />}
                onClick={() => redirect('list', 'berita')}
                sx={{ marginRight: 'auto' }}
            >
                Cancel
            </Button>
            <SaveButton />
        </Toolbar>
    );
};

export const BeritaEdit = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';
    const notify = useNotify();
    const redirect = useRedirect();
    const refresh = useRefresh();

    const onSuccess = () => {
        refresh();
        notify('Berita telah dikemaskini', { type: 'success' });
        redirect('list', 'berita');
    };

    return (
        <Edit
            title="Kemaskini Berita #%{id}"
            mutationOptions={{ onSuccess }}
        >
            <SimpleForm toolbar={<CustomEditToolbar />}>
                <Box display="flex" flexDirection="column" gap={2} width="100%">
                    <Typography variant="h6">Content</Typography>

                    <TextInput source="title_ms" label="Title (Malay)" fullWidth />
                    <TextInput source="title_en" label="Title (English)" fullWidth />

                    <TextInput
                        source="description_ms"
                        label="Description (Malay)"
                        multiline
                        rows={3}
                        fullWidth
                    />
                    <TextInput
                        source="description_en"
                        label="Description (English)"
                        multiline
                        rows={3}
                        fullWidth
                    />

                    <TextInput
                        source="content_ms"
                        label="Content (Malay)"
                        multiline
                        rows={10}
                        fullWidth
                    />
                    <TextInput
                        source="content_en"
                        label="Content (English)"
                        multiline
                        rows={10}
                        fullWidth
                    />

                    <ImageInput
                        source="image"
                        label="Gambar Utama"
                        accept={{ 'image/*': ['.png', '.jpg', '.jpeg', '.gif'] }}
                        maxSize={5000000}
                    >
                        <ImageField source="src" title="title" />
                    </ImageInput>

                    <Typography variant="h6" mt={2}>Settings</Typography>

                    <Box display="flex" gap={2}>
                        <BooleanInput source="is_active" label="Active" />
                        <BooleanInput source="is_featured" label="Featured" />
                    </Box>

                    <DateInput source="published_at" label="Published At" />

                    {isAdmin && (
                        <Box mt={2} p={2} sx={{ bgcolor: 'action.hover', borderRadius: 1 }}>
                            <Typography variant="subtitle2" sx={{ color: 'text.primary', fontWeight: 600 }}>
                                Audit Information
                            </Typography>
                            <FunctionField
                                render={(record: any) => (
                                    <Typography variant="body2" sx={{ color: 'text.primary' }}>
                                        Created By: <Box component="span" sx={{ fontWeight: 'bold' }}>{record?.creator?.name || 'N/A'}</Box>
                                    </Typography>
                                )}
                            />
                            <FunctionField
                                render={(record: any) => (
                                    <Typography variant="body2" sx={{ color: 'text.primary' }}>
                                        Updated By: <Box component="span" sx={{ fontWeight: 'bold' }}>{record?.updater?.name || 'N/A'}</Box>
                                    </Typography>
                                )}
                            />
                        </Box>
                    )}
                </Box>
            </SimpleForm>
        </Edit>
    );
};
