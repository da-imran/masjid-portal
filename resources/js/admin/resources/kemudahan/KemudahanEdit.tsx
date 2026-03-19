import {
    Edit,
    SimpleForm,
    TextInput,
    BooleanInput,
    ImageInput,
    ImageField,
    FunctionField,
    useGetIdentity,
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
                onClick={() => redirect('list', 'kemudahan')}
                sx={{ marginRight: 'auto' }}
            >
                Cancel
            </Button>
            <SaveButton />
        </Toolbar>
    );
};

export const KemudahanEdit = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';
    const notify = useNotify();
    const redirect = useRedirect();
    const refresh = useRefresh();

    const onSuccess = () => {
        refresh();
        notify('Kemudahan telah dikemaskini', { type: 'success' });
        redirect('list', 'kemudahan');
    };

    return (
        <Edit
            title="Kemaskini Kemudahan #%{id}"
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

                    <ImageInput
                        source="image"
                        label="Image"
                        accept={{ 'image/*': ['.png', '.jpg', '.jpeg', '.gif', '.webp'] }}
                        maxSize={5000000}
                    >
                        <ImageField source="src" title="title" />
                    </ImageInput>

                    <Typography variant="h6" mt={2}>Settings</Typography>

                    <BooleanInput source="is_active" label="Active" />

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
