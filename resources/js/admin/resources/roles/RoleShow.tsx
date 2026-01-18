import {
    Show,
    SimpleShowLayout,
    TextField,
    BooleanField,
    DateField,
    useGetIdentity,
    useRedirect,
    TopToolbar,
} from 'react-admin';
import { Box, Typography, Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const RoleShowActions = () => {
    const redirect = useRedirect();
    return (
        <TopToolbar>
            <Button
                startIcon={<ArrowBackIcon />}
                onClick={() => redirect('list', 'roles')}
            >
                Back
            </Button>
        </TopToolbar>
    );
};

export const RoleShow = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';

    // Only allow admin to access this page
    if (!isAdmin) {
        return null;
    }

    return (
        <Show actions={<RoleShowActions />}>
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
