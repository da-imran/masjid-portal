import {
    Show,
    SimpleShowLayout,
    TextField,
    ImageField,
    BooleanField,
    DateField,
    ReferenceField,
    useGetIdentity,
} from 'react-admin';

export const TakwimShow = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';

    return (
        <Show>
            <SimpleShowLayout>
                <TextField source="id" />
                <TextField source="title_ms" label="Title (Malay)" />
                <TextField source="title_en" label="Title (English)" />
                <TextField source="description_ms" label="Description (Malay)" />
                <TextField source="description_en" label="Description (English)" />
                <DateField source="event_date" label="Event Date" />
                <TextField source="event_time" label="Event Time" />
                <TextField source="location_ms" label="Location (Malay)" />
                <TextField source="location_en" label="Location (English)" />
                <ImageField source="image_name" label="Image" />
                <BooleanField source="is_active" label="Active" />
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
