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

function BeritaList() {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';

    return (
        <List sort={{ field: 'created_at', order: 'DESC' }}>
            <Datagrid rowClick="show" size="medium">
                <TextField source="id" />
                <TextField source="title_ms" label="Title (MS)" />
                <ImageField source="image_name" label="Image" sx={{ '& img': { maxWidth: 50, maxHeight: 50 } }} />
                <BooleanField source="is_active" label="Active" />
                <BooleanField source="is_featured" label="Featured" />
                <DateField source="created_at" label="Created" showTime />
                {isAdmin && <TextField source="creator.name" label="Created By" />}
                {isAdmin && <TextField source="updater.name" label="Updated By" />}
                <EditButton />
                <ShowButton />
            </Datagrid>
        </List>
    );
}

export { BeritaList };
