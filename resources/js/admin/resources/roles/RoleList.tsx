import {
    List,
    Datagrid,
    TextField,
    BooleanField,
    DateField,
    EditButton,
    ShowButton,
    useGetIdentity,
    useNotify,
    useRefresh,
    useRecordContext,
} from 'react-admin';
import { Button, Dialog, DialogTitle, DialogContent, DialogActions } from '@mui/material';
import { useState } from 'react';

const ConfirmDeleteButton = () => {
    const record = useRecordContext();
    const [open, setOpen] = useState(false);
    const notify = useNotify();
    const refresh = useRefresh();

    const handleOpen = () => setOpen(true);
    const handleClose = () => setOpen(false);

    const handleConfirm = async () => {
        if (!record) return;

        try {
            const token = localStorage.getItem('token');

            const response = await fetch(`/api/v1/admin/roles/${record.id}`, {
                method: 'DELETE',
                headers: new Headers({
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                }),
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to delete role');
            }

            notify('Peranan telah dipadam', { type: 'success' });
            refresh();
            handleClose();
        } catch (error: any) {
            notify('Gagal memadam peranan: ' + (error.message || 'Ralat tidak diketahui'), { type: 'error' });
        }
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
                aria-labelledby="delete-dialog-title"
                aria-describedby="delete-dialog-description"
            >
                <DialogTitle id="delete-dialog-title">
                    Padam Peranan?
                </DialogTitle>
                <DialogContent id="delete-dialog-description">
                    Adakah anda pasti mahu memadam peranan ini?
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

export const RoleList = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';

    // Only allow admin to access this page
    if (!isAdmin) {
        return null;
    }

    return (
        <List>
            <Datagrid rowClick={false}>
                <TextField source="id" label="ID" />
                <TextField source="name" label="Nama" />
                <TextField source="description" label="Penerangan" />
                <BooleanField source="is_active" label="Aktif" />
                <DateField source="created_at" label="Dicipta Pada" showTime />
                <DateField source="updated_at" label="Dikemaskini Pada" showTime />
                <EditButton />
                <ShowButton />
                <ConfirmDeleteButton />
            </Datagrid>
        </List>
    );
};