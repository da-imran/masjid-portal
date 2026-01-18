import {
    Show,
    SimpleShowLayout,
    TextField,
    ImageField,
    BooleanField,
    DateField,
    ReferenceField,
    useGetIdentity,
    useRedirect,
    TopToolbar,
} from 'react-admin';
import { Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

const BeritaShowActions = () => {
    const redirect = useRedirect();
    return (
        <TopToolbar>
            <Button
                startIcon={<ArrowBackIcon />}
                onClick={() => redirect('list', 'berita')}
            >
                Back
            </Button>
        </TopToolbar>
    );
};

export const BeritaShow = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';

    return (
        <Show actions={<BeritaShowActions />}>
            <SimpleShowLayout>
                <TextField source="id" />
                <TextField source="title_ms" label="Title (Malay)" />
                <TextField source="title_en" label="Title (English)" />
                <TextField source="description_ms" label="Description (Malay)" />
                <TextField source="description_en" label="Description (English)" />
                <TextField source="content_ms" label="Content (Malay)" />
                <TextField source="content_en" label="Content (English)" />
                <ImageField source="image_name" label="Image" />
                <BooleanField source="is_active" label="Active" />
                <BooleanField source="is_featured" label="Featured" />
                <DateField source="published_at" label="Published At" showTime />
                {isAdmin && (
                    <ReferenceField source="created_by" reference="users" label="Created By">
                        <TextField source="name" />
                    </ReferenceField>
                )}
                {isAdmin && (
                    <ReferenceField source="updated_by" reference="users" label="Updated By">
                        <TextField source="name" />
                    </ReferenceField>
                )}
                <DateField source="created_at" label="Created At" showTime />
                <DateField source="updated_at" label="Updated At" showTime />
            </SimpleShowLayout>
        </Show>
    );
};
