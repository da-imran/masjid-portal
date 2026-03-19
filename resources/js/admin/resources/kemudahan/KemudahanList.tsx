import {
    List,
    Datagrid,
    TextField,
    ImageField,
    BooleanField,
    DateField,
    EditButton,
    ShowButton,
    DeleteButton,
    useGetIdentity,
    useNotify,
    useRefresh,
} from 'react-admin';

function KemudahanList() {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';
    const notify = useNotify();
    const refresh = useRefresh();

    const handleDeleteSuccess = () => {
        notify('Kemudahan telah dipadam', { type: 'success' });
        refresh();
    };

    return (
        <List sort={{ field: 'created_at', order: 'DESC' }}>
            <Datagrid size="medium" rowClick={false}>
                <TextField source="id" />
                <TextField source="title_ms" label="Title (MS)" />
                <ImageField source="image_name" label="Image" src="image_name" sx={{ '& img': { maxWidth: 50, maxHeight: 50, objectFit: 'cover' } }} />
                <BooleanField source="is_active" label="Active" />
                <DateField source="created_at" label="Created" showTime />
                {isAdmin && <TextField source="creator.name" label="Created By" />}
                {isAdmin && <TextField source="updater.name" label="Updated By" />}
                <EditButton />
                <ShowButton />
                <DeleteButton
                    label="Padam"
                    mutationOptions={{ onSuccess: handleDeleteSuccess }}
                />
            </Datagrid>
        </List>
    );
}

export { KemudahanList };
