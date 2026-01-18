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
    useNotify,
    useRefresh,
    useUpdate,
    useRecordContext,
} from 'react-admin';
import { Button, Dialog, DialogTitle, DialogContent, DialogActions } from '@mui/material';
import { useState } from 'react';

const SoftDeleteButton = () => {
    const record = useRecordContext();
    const [open, setOpen] = useState(false);
    const notify = useNotify();
    const refresh = useRefresh();
    const [update] = useUpdate();

    const handleOpen = () => setOpen(true);
    const handleClose = () => setOpen(false);

    const handleConfirm = () => {
        if (!record) return;
        // Soft delete - set is_active to false
        update(
            'users',
            { id: record.id, data: { is_active: false }, previousData: record },
            {
                onSuccess: () => {
                    notify('Pengguna telah dipadam', { type: 'success' });
                    refresh();
                    handleClose();
                },
                onError: (error: any) => {
                    notify('Gagal memadamkan pengguna: ' + (error.message || 'Unknown error'), { type: 'error' });
                },
            },
        );
    };

    return (
        <>
            <Button
                onClick={handleOpen}
                color="error"
                size="small"
            >
                Padam
            </Button>
            <Dialog
                open={open}
                onClose={handleClose}
                aria-labelledby="alert-dialog-title"
                aria-describedby="alert-dialog-description"
            >
                <DialogTitle id="alert-dialog-title">
                    Padam Pengguna
                </DialogTitle>
                <DialogContent id="alert-dialog-description">
                    Adakah anda pasti mahu memadamkan pengguna ini dengan kekal?
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose} color="inherit">
                        Batal
                    </Button>
                    <Button onClick={handleConfirm} color="error" autoFocus>
                        Ya, Padam
                    </Button>
                </DialogActions>
            </Dialog>
        </>
    );
};

function UserList() {
    const { data: identity } = useGetIdentity();
    const { permissions } = usePermissions();
    const isAdmin = identity?.role === 'admin' || permissions?.includes('users.view');

    return (
        <List>
            <Datagrid rowClick="show" size="medium" bulkActionButtons={false}>
                <TextField source="id" />
                <TextField source="name" label="Nama"/>
                <EmailField source="email" label="E-mel"/>
                {isAdmin && <TextField source="role.name" label="Jenis Pengguna" />}
                <BooleanField source="is_active" label="Aktif?" />
                <BooleanField source="is_blocked" label="Disekat?" />
                <DateField source="created_at" label="Tarikh Dicipta" showTime />
                <EditButton label="Kemaskini"/>
                <ShowButton label="Lihat"/>
                <SoftDeleteButton />
            </Datagrid>
        </List>
    );
}

export { UserList };
