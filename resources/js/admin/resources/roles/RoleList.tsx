import {
    List,
    Datagrid,
    TextField,
    BooleanField,
    DateField,
    EditButton,
    ShowButton,
    DeleteButton,
    useGetIdentity,
    useNotify,
    useRefresh,
} from 'react-admin';

export const RoleList = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';
    const notify = useNotify();
    const refresh = useRefresh();

    const handleDeleteSuccess = () => {
        notify('Data telah dipadam', { type: 'success' });
        refresh();
    };

    // Only allow admin to access this page
    if (!isAdmin) {
        return null;
    }

    return (
        <List>
            <Datagrid rowClick="show">
                <TextField source="id" label="ID" />
                <TextField source="name" label="Name" />
                <TextField source="slug" label="Slug" />
                <TextField source="description" label="Description" />
                <BooleanField source="is_default" label="Default" />
                <DateField source="created_at" label="Created At" showTime />
                <DateField source="updated_at" label="Updated At" showTime />
                <EditButton />
                <ShowButton />
                <DeleteButton
                    label="Padam"
                    mutationOptions={{ onSuccess: handleDeleteSuccess }}
                />
            </Datagrid>
        </List>
    );
};
