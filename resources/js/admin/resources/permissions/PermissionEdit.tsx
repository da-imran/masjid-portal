import {
    Edit,
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

const CustomEditToolbar = () => {
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

export const PermissionEdit = () => {
    const redirect = useRedirect();
    const notify = useNotify();
    const refresh = useRefresh();

    const onSuccess = () => {
        notify('Kebenaran berjaya dikemaskini / Permission updated successfully', { type: 'success' });
        refresh();
        redirect('list', 'permissions');
    };

    const onError = (error: any) => {
        notify(error.message || 'Ralat mengemaskini kebenaran / Error updating permission', { type: 'error' });
    };

    // Parse the name to get module and action for the form
    const transform = (data: any) => {
        let name = data.name;

        // Build name from module and action if they're selected
        if (data.module && data.action) {
            name = `${data.module}.${data.action}`;
        }

        return {
            name,
            description: data.description,
            is_active: data.is_active,
            role_id: data.role_id,
        };
    };

    return (
        <Edit
            title="Kemaskini Kebenaran #%{id}"
            mutationOptions={{ onSuccess, onError }}
            transform={transform}
        >
            <SimpleForm toolbar={<CustomEditToolbar />}>
                <Box display="flex" flexDirection="column" gap={2} width="100%">
                    <Typography variant="h6">Maklumat Kebenaran / Permission Details</Typography>

                    <TextInput
                        source="name"
                        label="Nama Kebenaran / Permission Name"
                        fullWidth
                        disabled
                    />

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
        </Edit>
    );
};
