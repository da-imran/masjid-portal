import {
    Create,
    SimpleForm,
    TextInput,
    BooleanInput,
    ImageInput,
    ImageField,
    Toolbar,
    SaveButton,
    useRedirect,
    useNotify,
} from 'react-admin';
import { Box, Typography, Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const CustomCreateToolbar = () => {
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

export const KemudahanCreate = () => {
    const notify = useNotify();
    const redirect = useRedirect();

    const onSuccess = () => {
        notify('Kemudahan telah ditambah', { type: 'success' });
        redirect('list', 'kemudahan');
    };

    return (
        <Create
            title="Cipta Kemudahan"
            mutationOptions={{ onSuccess }}
        >
            <SimpleForm toolbar={<CustomCreateToolbar />}>
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

                    <BooleanInput source="is_active" label="Active" defaultValue={true} />
                </Box>
            </SimpleForm>
        </Create>
    );
};
