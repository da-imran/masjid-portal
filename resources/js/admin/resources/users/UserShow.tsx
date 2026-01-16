import React from 'react';
import {
    Show,
    SimpleShowLayout,
    TextField,
    EmailField,
    BooleanField,
    DateField,
    ReferenceField,
    useGetIdentity,
} from 'react-admin';

export const UserShow = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';

    return (
        <Show>
            <SimpleShowLayout>
                <TextField source="id" />
                <TextField source="name" />
                <EmailField source="email" />
                {isAdmin && (
                    <ReferenceField source="role_id" reference="roles" label="Role">
                        <TextField source="name" />
                    </ReferenceField>
                )}
                <BooleanField source="is_blocked" label="Blocked" />
                <DateField source="blocked_at" label="Blocked At" showTime />
                <TextField source="blocked_reason" label="Blocked Reason" />
                <DateField source="created_at" label="Created At" showTime />
                <DateField source="updated_at" label="Updated At" showTime />
            </SimpleShowLayout>
        </Show>
    );
};
