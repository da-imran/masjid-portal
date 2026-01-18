import {
    Create,
    SimpleForm,
    TextInput,
    PasswordInput,
    ReferenceInput,
    SelectInput,
    useGetIdentity,
    Toolbar,
    SaveButton,
    useRedirect,
    useNotify,
} from 'react-admin';
import { Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const CustomCreateToolbar = () => {
    const redirect = useRedirect();

    return (
        <Toolbar>
            <Button
                startIcon={<ArrowBackIcon />}
                onClick={() => redirect('list', 'users')}
                sx={{ marginRight: 'auto' }}
            >
                Cancel
            </Button>
            <SaveButton />
        </Toolbar>
    );
};

export const UserCreate = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';
    const notify = useNotify();
    const redirect = useRedirect();

    const onSuccess = () => {
        notify('Pengguna telah ditambah', { type: 'success' });
        redirect('list', 'users');
    };

    return (
        <Create
            title="Cipta Pengguna"
            mutationOptions={{ onSuccess }}
        >
            <SimpleForm toolbar={<CustomCreateToolbar />}>
                <TextInput source="name" fullWidth label="Nama"/>
                <TextInput source="email" type="email" fullWidth label="E-mel"/>
                <PasswordInput source="password" fullWidth />
                {isAdmin && (
                    <ReferenceInput source="role_id" reference="roles">
                        <SelectInput optionText="name" fullWidth label="Jenis Pengguna"/>
                    </ReferenceInput>
                )}
            </SimpleForm>
        </Create>
    );
};
