import {
    Create,
    SimpleForm,
    TextInput,
    DateInput,
    BooleanInput,
    Toolbar,
    SaveButton,
    useRedirect,
    useNotify,
} from 'react-admin';
import { Box, Typography, Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const CustomCreateToolbar = () => {
    const redirect = useRedirect();

    return (
        <Toolbar>
            <Button
                startIcon={<ArrowBackIcon />}
                onClick={() => redirect('list', 'takwim')}
                sx={{ marginRight: 'auto' }}
            >
                Cancel
            </Button>
            <SaveButton />
        </Toolbar>
    );
};

export const TakwimCreate = () => {
    const notify = useNotify();
    const redirect = useRedirect();

    const onSuccess = () => {
        notify('Event created successfully', { type: 'success' });
        redirect('list', 'takwim');
    };

    return (
        <Create
            title="Create Event"
            mutationOptions={{ onSuccess }}
        >
            <SimpleForm toolbar={<CustomCreateToolbar />}>
                <Box display="flex" flexDirection="column" gap={2} width="100%">
                    <Typography variant="h6">Content</Typography>

                    <TextInput source="title_ms" label="Title (Malay)" fullWidth />
                    <TextInput source="title_en" label="Title (English)" fullWidth />

                    <TextInput
                        source="description_ms"
                        label="Description (Malay)"
                        multiline
                        rows={3}
                        fullWidth
                    />
                    <TextInput
                        source="description_en"
                        label="Description (English)"
                        multiline
                        rows={3}
                        fullWidth
                    />

                    <TextInput source="location_ms" label="Location (Malay)" fullWidth />
                    <TextInput source="location_en" label="Location (English)" fullWidth />

                    <TextInput source="image_name" label="Image Name" fullWidth />

                    <Typography variant="h6" mt={2}>Event Details</Typography>

                    <DateInput source="event_date" label="Event Date" fullWidth />
                    <TextInput source="event_time" label="Event Time" type="time" fullWidth />

                    <Typography variant="h6" mt={2}>Settings</Typography>

                    <BooleanInput source="is_active" label="Active" defaultValue={true} />
                </Box>
            </SimpleForm>
        </Create>
    );
};
