import {
    Create,
    SimpleForm,
    TextInput,
    BooleanInput,
    Toolbar,
    SaveButton,
    useRedirect,
    useGetIdentity,
    useNotify,
    required,
} from 'react-admin';
import { Box, Typography, Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const CustomCreateToolbar = () => {
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

export const RoleCreate = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';
    const notify = useNotify();
    const redirect = useRedirect();

    const onSuccess = () => {
        notify('Data telah ditambah', { type: 'success' });
        redirect('list', 'roles');
    };

    // Only allow admin to access this page
    if (!isAdmin) {
        return null;
    }

    return (
        <Create
            title="Cipta Peranan"
            mutationOptions={{ onSuccess }}
        >
            <SimpleForm toolbar={<CustomCreateToolbar />}>
                <Box display="flex" flexDirection="column" gap={2} width="100%">
                    <Typography variant="h6">Maklumat Peranan</Typography>

                    <TextInput source="name" label="Nama" fullWidth validate={required()} />

                    <TextInput
                        source="description"
                        label="Penerangan"
                        multiline
                        rows={3}
                        fullWidth
                    />

                    <Typography variant="h6" mt={2}>Tetapan</Typography>

                    <BooleanInput source="is_active" label="Aktif" defaultValue={true} />
                </Box>
            </SimpleForm>
        </Create>
    );
};
