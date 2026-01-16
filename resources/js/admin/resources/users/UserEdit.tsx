import {
    Edit,
    SimpleForm,
    TextInput,
    PasswordInput,
    ReferenceInput,
    SelectInput,
    BooleanInput,
    useGetIdentity,
    Toolbar,
    SaveButton,
    useRedirect,
} from 'react-admin';
import { Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const CustomEditToolbar = () => {
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

export const UserEdit = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';

    return (
        <Edit title="Edit User #%{id}">
            <SimpleForm toolbar={<CustomEditToolbar />}>
                <TextInput source="name" fullWidth />
                <TextInput source="email" type="email" fullWidth />
                <PasswordInput source="password" helperText="Leave empty to keep current password" fullWidth />
                {isAdmin && (
                    <ReferenceInput source="role_id" reference="roles">
                        <SelectInput optionText="name" fullWidth />
                    </ReferenceInput>
                )}
                {isAdmin && (
                    <BooleanInput source="is_blocked" label="Blocked" />
                )}
            </SimpleForm>
        </Edit>
    );
};
