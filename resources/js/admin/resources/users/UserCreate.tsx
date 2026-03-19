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
                Batal / Cancel
            </Button>
            <SaveButton />
        </Toolbar>
    );
};

// Email validation regex
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

export const UserCreate = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';
    const notify = useNotify();
    const redirect = useRedirect();

    const onSuccess = () => {
        notify('Pengguna telah ditambah / User created successfully', { type: 'success' });
        redirect('list', 'users');
    };

    const validateEmail = (value: string) => {
        if (!value) {
            return 'E-mel diperlukan / Email is required';
        }
        if (!emailRegex.test(value)) {
            return 'Sila masukkan e-mel yang sah / Please enter a valid email';
        }
        return undefined;
    };

    const validatePasswords = (values: any) => {
        const errors: any = {};

        // Name validation
        if (!values.name) {
            errors.name = 'Nama diperlukan / Name is required';
        }

        // Email validation
        if (!values.email) {
            errors.email = 'E-mel diperlukan / Email is required';
        } else if (!emailRegex.test(values.email)) {
            errors.email = 'Sila masukkan e-mel yang sah / Please enter a valid email';
        }

        // Password validation
        if (!values.password) {
            errors.password = 'Kata laluan diperlukan / Password is required';
        } else if (values.password.length < 8) {
            errors.password = 'Kata laluan mestilah sekurang-kurangnya 8 aksara / Password must be at least 8 characters';
        }

        // Password confirmation validation
        if (!values.password_confirmation) {
            errors.password_confirmation = 'Sahkan kata laluan diperlukan / Confirm password is required';
        } else if (values.password !== values.password_confirmation) {
            errors.password_confirmation = 'Pengesahan kata laluan tidak sepadan / Password confirmation does not match';
        }

        return Object.keys(errors).length ? errors : undefined;
    };

    return (
        <Create
            title="Cipta Pengguna / Create User"
            mutationOptions={{ onSuccess }}
        >
            <SimpleForm
                toolbar={<CustomCreateToolbar />}
                validate={validatePasswords}
            >
                <TextInput
                    source="name"
                    fullWidth
                    label="Nama / Name"
                    required
                />
                <TextInput
                    source="email"
                    type="email"
                    fullWidth
                    label="E-mel / Email"
                    required
                />
                <PasswordInput
                    source="password"
                    fullWidth
                    label="Kata Laluan / Password"
                    required
                />
                <PasswordInput
                    source="password_confirmation"
                    fullWidth
                    label="Sahkan Kata Laluan / Confirm Password"
                    required
                />
                {isAdmin && (
                    <ReferenceInput source="role_id" reference="roles">
                        <SelectInput optionText="name" fullWidth label="Jenis Pengguna / User Type" />
                    </ReferenceInput>
                )}
            </SimpleForm>
        </Create>
    );
};
