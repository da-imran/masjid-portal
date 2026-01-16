import {
    List,
    Datagrid,
    TextField,
    ImageField,
    BooleanField,
    DateField,
    NumberField,
    EditButton,
    ShowButton,
    useGetIdentity,
} from 'react-admin';

function KemudahanList() {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role === 'admin';

    return (
        <List sort={{ field: 'order_column', order: 'ASC' }}>
            <Datagrid rowClick="show" size="medium">
                <TextField source="id" />
                <TextField source="title_ms" label="Title (MS)" />
                <TextField source="icon_name" label="Icon" />
                <ImageField source="image_name" label="Image" sx={{ '& img': { maxWidth: 50, maxHeight: 50 } }} />
                <NumberField source="order_column" label="Order" />
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

export { KemudahanList };
