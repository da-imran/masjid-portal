import {
    Show,
    SimpleShowLayout,
    TextField,
    EmailField,
    BooleanField,
    DateField,
    ReferenceField,
    useGetIdentity,
    useRedirect,
    TopToolbar,
} from 'react-admin';
import { Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const UserShowActions = () => {
    const redirect = useRedirect();
    return (
        <TopToolbar>
            <Button
                startIcon={<ArrowBackIcon />}
                onClick={() => redirect('list', 'users')}
            >
                Back
            </Button>
        </TopToolbar>
    );
};

export const UserShow = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';

    return (
        <Show actions={<UserShowActions />}>
            <SimpleShowLayout>
                <TextField source="id" />
                <TextField source="name" label="Nama"/>
                <EmailField source="email" label="E-mel"/>
                {isAdmin && (
                    <ReferenceField source="role_id" reference="roles" label="Jenis Pengguna">
                        <TextField source="name" />
                    </ReferenceField>
                )}
                <BooleanField source="is_active" label="Aktif?" />
                <BooleanField source="is_blocked" label="Disekat?" />
                <DateField source="blocked_at" label="Disekat Pada" showTime />
                <TextField source="blocked_reason" label="Alasan Disekat" />
                <DateField source="created_at" label="Tarikh Dicipta" showTime />
                <DateField source="updated_at" label="Tarikh Dikemaskini" showTime />
            </SimpleShowLayout>
        </Show>
    );
};
