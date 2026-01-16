import {
    Show,
    SimpleShowLayout,
    TextField,
    ImageField,
    BooleanField,
    NumberField,
    DateField,
    ReferenceField,
    useGetIdentity,
} from 'react-admin';

export const KemudahanShow = () => {
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
                <TextField source="icon_name" label="Icon" />
                <ImageField source="image_name" label="Image" />
                <NumberField source="order_column" label="Order" />
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
