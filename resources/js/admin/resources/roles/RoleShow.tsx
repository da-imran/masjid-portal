import {
    Show,
    SimpleShowLayout,
    TextField,
    BooleanField,
    DateField,
    useShowController,
} from 'react-admin';
import { Box, Typography, Chip } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import { Button } from '@mui/material';
import { useRedirect } from 'react-admin';

export const RoleShow = () => {
    const redirect = useRedirect();

    return (
        <Show>
            <Box display="flex" alignItems="center" mb={2}>
                <Button
                    startIcon={<ArrowBackIcon />}
                    onClick={() => redirect('list', 'roles')}
                    sx={{ marginRight: 2 }}
                >
                    Back to List
                </Button>
            </Box>
            <SimpleShowLayout>
                <TextField source="id" label="ID" />
                <TextField source="name" label="Name" />
                <TextField source="slug" label="Slug" />
                <TextField source="description" label="Description" />
                <BooleanField source="is_default" label="Default Role" />
                <DateField source="created_at" label="Created At" showTime />
                <DateField source="updated_at" label="Updated At" showTime />
            </SimpleShowLayout>
        </Show>
    );
};
