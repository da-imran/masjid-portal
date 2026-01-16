import {
    Create,
    SimpleForm,
    TextInput,
    NumberInput,
    BooleanInput,
    Toolbar,
    SaveButton,
    useRedirect,
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
    return (
        <Create title="Create Kemudahan">
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

                    <TextInput source="icon_name" label="Icon Name" fullWidth />
                    <TextInput source="image_name" label="Image Name" fullWidth />

                    <Typography variant="h6" mt={2}>Settings</Typography>

                    <NumberInput source="order_column" label="Order Column" defaultValue={0} fullWidth />
                    <BooleanInput source="is_active" label="Active" defaultValue={true} />
                </Box>
            </SimpleForm>
        </Create>
    );
};
