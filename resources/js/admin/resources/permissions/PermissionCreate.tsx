import {
    Create,
    SimpleForm,
    TextInput,
    SelectInput,
    BooleanInput,
    Toolbar,
    SaveButton,
    useRedirect,
    useNotify,
    useRefresh,
} from 'react-admin';
import { Box, Typography, Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import { PERMISSION_MODULES, PERMISSION_ACTIONS } from '@/types';

const CustomCreateToolbar = () => {
    const redirect = useRedirect();

    return (
        <Toolbar>
            <Button
                startIcon={<ArrowBackIcon />}
                onClick={() => redirect('list', 'permissions')}
                sx={{ marginRight: 'auto' }}
            >
                Cancel
            </Button>
            <SaveButton />
        </Toolbar>
    );
};

export const PermissionCreate = () => {
    const redirect = useRedirect();
    const notify = useNotify();
    const refresh = useRefresh();

    const onSuccess = () => {
        notify('Kebenaran berjaya dicipta / Permission created successfully', { type: 'success' });
        refresh();
        redirect('list', 'permissions');
    };

    const onError = (error: any) => {
        notify(error.message || 'Ralat mencipta kebenaran / Error creating permission', { type: 'error' });
    };

    return (
        <Create
            title="Cipta Kebenaran Baru / Create New Permission"
            mutationOptions={{ onSuccess, onError }}
        >
            <SimpleForm toolbar={<CustomCreateToolbar />}>
                <Box display="flex" flexDirection="column" gap={2} width="100%">
                    <Typography variant="h6">Maklumat Kebenaran / Permission Details</Typography>

                    <SelectInput
                        source="module"
                        label="Modul / Module"
                        choices={PERMISSION_MODULES.map((m) => ({
                            id: m.key,
                            name: m.label,
                        }))}
                        fullWidth
                        required
                    />

                    <SelectInput
                        source="action"
                        label="Tindakan / Action"
                        choices={PERMISSION_ACTIONS.map((a) => ({
                            id: a.key,
                            name: a.label,
                        }))}
                        fullWidth
                        required
                    />

                    <TextInput
                        source="description"
                        label="Penerangan / Description"
                        multiline
                        rows={3}
                        fullWidth
                    />

                    <Typography variant="h6" mt={2}>Tetapan / Settings</Typography>

                    <BooleanInput source="is_active" label="Aktif / Active" />
                </Box>
            </SimpleForm>
        </Create>
    );
};
