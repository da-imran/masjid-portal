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
    const isAdmin = identity?.role?.toLowerCase() === 'admin';
    const notify = useNotify();
    const redirect = useRedirect();
    const refresh = useRefresh();

    const onSuccess = () => {
        notify('Data telah dikemaskini', { type: 'success' });
        refresh();
        redirect('list', 'roles');
    };

    const onError = (error: any) => {
        notify(error.message || 'Gagal mengemaskini data', { type: 'error' });
    };

    // Only allow admin to access this page
    if (!isAdmin) {
        return null;
    }

    return (
        <Edit
            title="Kemaskini Peranan #%{id}"
            mutationMode="pessimistic"
            mutationOptions={{ onSuccess, onError }}
        >
            <SimpleForm toolbar={<CustomEditToolbar />}>
                <Box display="flex" flexDirection="column" gap={2} width="100%">
                    <Typography variant="h6">Maklumat Peranan</Typography>

                    <TextInput source="name" label="Nama" fullWidth />

                    <TextInput
                        source="description"
                        label="Penerangan"
                        multiline
                        rows={3}
                        fullWidth
                    />

                    <Typography variant="h6" mt={2}>Tetapan</Typography>

                    <BooleanInput source="is_active" label="Aktif" />
                </Box>
            </SimpleForm>
        </Edit>
    );
};
