import {
    Show,
    SimpleShowLayout,
    TextField,
    BooleanField,
    DateField,
    ArrayField,
    SingleFieldList,
    useGetIdentity,
    useRedirect,
    TopToolbar,
} from 'react-admin';
import { Button } from '@mui/material';
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
    const isAdmin = identity?.role?.toLowerCase() === 'admin';

    // Only allow admin to access this page
    if (!isAdmin) {
        return null;
    }

    return (
        <Show actions={<RoleShowActions />}>
            <SimpleShowLayout>
                <TextField source="id" label="ID" />
                <TextField source="name" label="Nama" />
                <TextField source="description" label="Penerangan" />
                <BooleanField source="is_active" label="Aktif" />
                <ArrayField source="permissions" label="Kebenaran">
                    <SingleFieldList>
                        <TextField source="name" label="Nama" />
                        <TextField source="description" label="Penerangan" />
                    </SingleFieldList>
                </ArrayField>
                <DateField source="created_at" label="Dicipta Pada" showTime />
                <DateField source="updated_at" label="Dikemaskini Pada" showTime />
            </SimpleShowLayout>
        </Show>
    );
};
