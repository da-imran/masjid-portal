import {
    List,
    Datagrid,
    TextField,
    ImageField,
    BooleanField,
    DateField,
    EditButton,
    ShowButton,
    useGetIdentity,
} from 'react-admin';

function TakwimList() {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';

    return (
        <List sort={{ field: 'event_date', order: 'ASC' }}>
            <Datagrid rowClick="show" size="medium">
                <TextField source="id" />
                <TextField source="title_ms" label="Title (MS)" />
                <DateField source="event_date" label="Event Date" />
                <TextField source="event_time" label="Time" />
                <TextField source="location_ms" label="Location (MS)" />
                <ImageField source="image_name" label="Image" sx={{ '& img': { maxWidth: 50, maxHeight: 50 } }} />
                <BooleanField source="is_active" label="Active" />
                <DateField source="created_at" label="Created" showTime />
                {isAdmin && <TextField source="creator.name" label="Created By" />}
                {isAdmin && <TextField source="updater.name" label="Updated By" />}
                <EditButton />
                <ShowButton />
            </Datagrid>
        </List>
    );
}

export { TakwimList };
