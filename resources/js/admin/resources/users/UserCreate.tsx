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

    return (
        <Create title="Create User">
            <SimpleForm toolbar={<CustomCreateToolbar />}>
                <TextInput source="name" fullWidth />
                <TextInput source="email" type="email" fullWidth />
                <PasswordInput source="password" fullWidth />
                {isAdmin && (
                    <ReferenceInput source="role_id" reference="roles">
                        <SelectInput optionText="name" fullWidth />
                    </ReferenceInput>
                )}
            </SimpleForm>
        </Create>
    );
};
