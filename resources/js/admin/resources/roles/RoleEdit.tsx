import {
    Edit,
    SimpleForm,
    TextInput,
    BooleanInput,
    Toolbar,
    SaveButton,
    useRedirect,
    useGetIdentity,
    useNotify,
} from 'react-admin';
import { Box, Typography, Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const CustomEditToolbar = () => {
    const redirect = useRedirect();

    return (
        <Toolbar>
            <Button
                startIcon={<ArrowBackIcon />}
                onClick={() => redirect('list', 'roles')}
                sx={{ marginRight: 'auto' }}
            >
                Cancel
            </Button>
            <SaveButton />
        </Toolbar>
    );
};

export const RoleEdit = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';
    const notify = useNotify();
    const redirect = useRedirect();

    const onSuccess = () => {
        notify('Data telah dikemaskini', { type: 'success' });
        redirect('list', 'roles');
    };

    // Only allow admin to access this page
    if (!isAdmin) {
        return null;
    }

    return (
        <Edit
            title="Kemaskini Jenis Pengguna #%{id}"
            mutationOptions={{ onSuccess }}
        >
            <SimpleForm toolbar={<CustomEditToolbar />}>
                <Box display="flex" flexDirection="column" gap={2} width="100%">
                    <Typography variant="h6">Role Information</Typography>

                    <TextInput source="name" label="Name" fullWidth disabled />
                    <TextInput source="slug" label="Slug" fullWidth disabled />

                    <TextInput
                        source="description"
                        label="Description"
                        multiline
                        rows={3}
                        fullWidth
                    />

                    <Typography variant="h6" mt={2}>Settings</Typography>

                    <BooleanInput source="is_default" label="Default Role" />
                </Box>
            </SimpleForm>
        </Edit>
    );
};
