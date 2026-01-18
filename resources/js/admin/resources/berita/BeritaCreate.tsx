import {
    Create,
    SimpleForm,
    TextInput,
    BooleanInput,
    DateInput,
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
                onClick={() => redirect('list', 'berita')}
                sx={{ marginRight: 'auto' }}
            >
                Cancel
            </Button>
            <SaveButton />
        </Toolbar>
    );
};

export const BeritaCreate = () => {
    const notify = useNotify();
    const redirect = useRedirect();

    const onSuccess = () => {
        notify('Berita telah ditambah', { type: 'success' });
        redirect('list', 'berita');
    };

    return (
        <Create
            title="Cipta Berita"
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
                        <BooleanInput source="is_active" label="Active" defaultValue={true} />
                        <BooleanInput source="is_featured" label="Featured" defaultValue={false} />
                    </Box>

                    <DateInput source="published_at" label="Published At" defaultValue={new Date()} />
                </Box>
            </SimpleForm>
        </Create>
    );
};
