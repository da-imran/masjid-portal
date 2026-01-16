import {
    Edit,
    SimpleForm,
    TextInput,
    NumberInput,
    BooleanInput,
    useGetIdentity,
    Toolbar,
    SaveButton,
    useRedirect,
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
    const isAdmin = identity?.role === 'admin';

    return (
        <Edit title="Edit Kemudahan #%{id}">
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

                    <TextInput source="icon_name" label="Icon Name" fullWidth />
                    <TextInput source="image_name" label="Image Name" fullWidth />

                    <Typography variant="h6" mt={2}>Settings</Typography>

                    <NumberInput source="order_column" label="Order Column" fullWidth />
                    <BooleanInput source="is_active" label="Active" />

                    {isAdmin && (
                        <Box mt={2} p={2} bgcolor="grey.100" borderRadius={1}>
                            <Typography variant="subtitle2" color="textSecondary">
                                Audit Information
                            </Typography>
                            <Typography variant="body2">
                                Created By: <strong>{identity?.fullName || 'N/A'}</strong>
                            </Typography>
                        </Box>
                    )}
                </Box>
            </SimpleForm>
        </Edit>
    );
};
