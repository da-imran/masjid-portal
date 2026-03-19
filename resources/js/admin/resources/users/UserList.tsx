import {
    List,
    Datagrid,
    TextField,
    EmailField,
    BooleanField,
    DateField,
    EditButton,
    ShowButton,
    DeleteButton,
    useGetIdentity,
    useNotify,
    useRefresh,
    useRecordContext,
} from 'react-admin';
import { Button, Dialog, DialogTitle, DialogContent, DialogActions } from '@mui/material';
import { useState } from 'react';

const DisableButton = () => {
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

            const response = await fetch(`/api/v1/admin/users/${record.id}/disable`, {
                method: 'POST',
                headers: new Headers({
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                }),
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to disable user');
            }

            notify('User disabled successfully', { type: 'success' });
            refresh();
            handleClose();
        } catch (error: any) {
            notify('Failed to disable user: ' + (error.message || 'Unknown error'), { type: 'error' });
        }
    };

    return (
        <>
            <Button
                onClick={handleOpen}
                color="warning"
                size="small"
            >
                Disabled
            </Button>
            <Dialog
                open={open}
                onClose={handleClose}
                aria-labelledby="disable-dialog-title"
                aria-describedby="disable-dialog-description"
            >
                <DialogTitle id="disable-dialog-title">
                    Disable User
                </DialogTitle>
                <DialogContent id="disable-dialog-description">
                    Are you sure to disable this user?
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose} color="inherit">
                        Cancel
                    </Button>
                    <Button onClick={handleConfirm} color="warning" autoFocus>
                        Yes, Disable
                    </Button>
                </DialogActions>
            </Dialog>
        </>
    );
};

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

            const response = await fetch(`/api/v1/admin/users/${record.id}`, {
                method: 'DELETE',
                headers: new Headers({
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                }),
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to remove user');
            }

            notify('User removed successfully', { type: 'success' });
            refresh();
            handleClose();
        } catch (error: any) {
            notify('Failed to remove user: ' + (error.message || 'Unknown error'), { type: 'error' });
        }
    };

    return (
        <>
            <Button
                onClick={handleOpen}
                color="error"
                size="small"
            >
                Remove
            </Button>
            <Dialog
                open={open}
                onClose={handleClose}
                aria-labelledby="delete-dialog-title"
                aria-describedby="delete-dialog-description"
            >
                <DialogTitle id="delete-dialog-title">
                    Remove User
                </DialogTitle>
                <DialogContent id="delete-dialog-description">
                    This will permanently remove this user from database. Are you sure to remove this user?
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose} color="inherit">
                        Cancel
                    </Button>
                    <Button onClick={handleConfirm} color="error" autoFocus>
                        Yes, Remove
                    </Button>
                </DialogActions>
            </Dialog>
        </>
    );
};

function UserList() {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';

    return (
        <List>
            <Datagrid size="medium" bulkActionButtons={false} rowClick={false}>
                <TextField source="id" />
                <TextField source="name" label="Nama"/>
                <EmailField source="email" label="E-mel"/>
                {isAdmin && <TextField source="role.name" label="Jenis Pengguna" />}
                <BooleanField source="is_active" label="Aktif?" />
                <BooleanField source="is_blocked" label="Disekat?" />
                <DateField source="created_at" label="Tarikh Dicipta" showTime />
                <EditButton label="Kemaskini"/>
                <ShowButton label="Lihat"/>
                {isAdmin && <DisableButton />}
                {isAdmin && <ConfirmDeleteButton />}
            </Datagrid>
        </List>
    );
}

export { UserList };
