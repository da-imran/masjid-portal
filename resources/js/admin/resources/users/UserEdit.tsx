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
    FormDataConsumer,
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
        <Edit>
            <SimpleForm toolbar={<CustomEditToolbar />}>
                <TextInput source="name" fullWidth />
                <TextInput source="email" type="email" fullWidth />
                <PasswordInput source="password" helperText="Leave empty to keep current password" fullWidth />
                {isAdmin && (
                    <ReferenceInput source="role_id" reference="roles">
                        <SelectInput optionText="name" fullWidth />
                    </ReferenceInput>
                )}
                <BooleanInput source="is_active" label="Aktif" />
                {isAdmin && (
                    <>
                        <BooleanInput source="is_blocked" label="Disekat" />
                        <FormDataConsumer>
                            {({ formData }) =>
                                formData?.is_blocked && (
                                    <TextInput
                                        source="blocked_reason"
                                        label="Alasan Disekat"
                                        multiline
                                        rows={3}
                                        fullWidth
                                        helperText="Sila nyatakan sebab pengguna ini disekat"
                                    />
                                )
                            }
                        </FormDataConsumer>
                    </>
                )}
            </SimpleForm>
        </Edit>
    );
};
