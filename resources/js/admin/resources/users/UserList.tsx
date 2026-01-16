import {
    List,
    Datagrid,
    TextField,
    EmailField,
    BooleanField,
    DateField,
    EditButton,
    ShowButton,
    useGetIdentity,
    usePermissions,
} from 'react-admin';

function UserList() {
    const { data: identity } = useGetIdentity();
    const { permissions } = usePermissions();

    const isAdmin = identity?.role === 'admin' || permissions?.includes('users.view');

    return (
        <List>
            <Datagrid rowClick="show" size="medium">
                <TextField source="id" />
                <TextField source="name" />
                <EmailField source="email" />
                {isAdmin && <TextField source="role.name" label="Role" />}
                <BooleanField source="is_blocked" label="Blocked" />
                <DateField source="created_at" label="Created" showTime />
                <EditButton />
                <ShowButton />
            </Datagrid>
        </List>
    );
}

export { UserList };
