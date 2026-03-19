import {
    Edit,
    SimpleForm,
    TextInput,
    DateInput,
    BooleanInput,
    FunctionField,
    useGetIdentity,
    Toolbar,
    SaveButton,
    useRedirect,
    useNotify,
    useRefresh,
} from 'react-admin';
import { Box, Typography, Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const CustomEditToolbar = () => {
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

export const TakwimEdit = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';
    const notify = useNotify();
    const redirect = useRedirect();
    const refresh = useRefresh();

    const onSuccess = () => {
        refresh();
        notify('Event updated successfully', { type: 'success' });
        redirect('list', 'takwim');
    };

    return (
        <Edit
            title="Edit Event #%{id}"
            mutationOptions={{ onSuccess }}
        >
            <SimpleForm toolbar={<CustomEditToolbar />}>
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

                    <BooleanInput source="is_active" label="Active" />

                    {isAdmin && (
                        <Box mt={2} p={2} sx={{ bgcolor: 'action.hover', borderRadius: 1 }}>
                            <Typography variant="subtitle2" sx={{ color: 'text.primary', fontWeight: 600 }}>
                                Audit Information
                            </Typography>
                            <FunctionField
                                render={(record: any) => (
                                    <Typography variant="body2" sx={{ color: 'text.primary' }}>
                                        Created By: <Box component="span" sx={{ fontWeight: 'bold' }}>{record?.creator?.name || 'N/A'}</Box>
                                    </Typography>
                                )}
                            />
                            <FunctionField
                                render={(record: any) => (
                                    <Typography variant="body2" sx={{ color: 'text.primary' }}>
                                        Updated By: <Box component="span" sx={{ fontWeight: 'bold' }}>{record?.updater?.name || 'N/A'}</Box>
                                    </Typography>
                                )}
                            />
                        </Box>
                    )}
                </Box>
            </SimpleForm>
        </Edit>
    );
};
