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
                Batal / Cancel
            </Button>
            <SaveButton />
        </Toolbar>
    );
};

const validatePasswords = (values: any) => {
    const errors: any = {};

    // Only validate password fields if password is provided
    if (values.password) {
        if (values.password.length < 8) {
            errors.password = 'Kata laluan mestilah sekurang-kurangnya 8 aksara / Password must be at least 8 characters';
        }

        // If password is provided, confirmation should also be provided and match
        if (!values.password_confirmation) {
            errors.password_confirmation = 'Sahkan kata laluan diperlukan bila menukar kata laluan / Confirm password required when changing password';
        } else if (values.password !== values.password_confirmation) {
            errors.password_confirmation = 'Pengesahan kata laluan tidak sepadan / Password confirmation does not match';
        } else if (values.password_confirmation && !values.password) {
            errors.password = 'Kata laluan diperlukan bila memberikan pengesahan / Password required when confirming';
        }
    }

    // If password_confirmation is provided but password is not
    if (values.password_confirmation && !values.password) {
        errors.password = 'Kata laluan diperlukan / Password is required';
    }

    return Object.keys(errors).length ? errors : undefined;
};

export const UserEdit = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';

    return (
        <Edit>
            <SimpleForm
                toolbar={<CustomEditToolbar />}
                validate={validatePasswords}
            >
                <TextInput source="name" fullWidth label="Nama / Name" />
                <TextInput source="email" type="email" fullWidth label="E-mel / Email" />
                <PasswordInput
                    source="password"
                    helperText="Biarkan kosong untuk mengekalkan kata laluan semasa / Leave empty to keep current password"
                    fullWidth
                    label="Kata Laluan / Password"
                />
                <PasswordInput
                    source="password_confirmation"
                    helperText="Sila masukkan kata laluan baharu lagi untuk pengesahan / Please re-enter new password for confirmation"
                    fullWidth
                    label="Sahkan Kata Laluan / Confirm Password"
                />
                {isAdmin && (
                    <ReferenceInput source="role_id" reference="roles">
                        <SelectInput optionText="name" fullWidth label="Jenis Pengguna / User Type" />
                    </ReferenceInput>
                )}
                <BooleanInput source="is_active" label="Aktif / Active" />
                {isAdmin && (
                    <>
                        <BooleanInput source="is_blocked" label="Disekat / Blocked" />
                        <FormDataConsumer>
                            {({ formData }) =>
                                formData?.is_blocked && (
                                    <TextInput
                                        source="blocked_reason"
                                        label="Alasan Disekat / Blocked Reason"
                                        multiline
                                        rows={3}
                                        fullWidth
                                        helperText="Sila nyatakan sebab mengapa pengguna ini disekat"
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
