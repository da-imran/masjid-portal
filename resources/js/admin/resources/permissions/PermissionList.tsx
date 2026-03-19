import {
    useGetList,
    useGetIdentity,
    useNotify,
    useRefresh,
    useDataProvider,
} from 'react-admin';
import { useState } from 'react';
import {
    Box,
    Card,
    CardContent,
    CardHeader,
    Typography,
    Button,
    Dialog,
    DialogTitle,
    DialogContent,
    DialogActions,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableHead,
    TableRow,
    Checkbox,
    Chip,
    IconButton,
    TextField,
    Tooltip,
} from '@mui/material';
import AddIcon from '@mui/icons-material/Add';
import SaveIcon from '@mui/icons-material/Save';
import { Role, PERMISSION_MODULES, PERMISSION_ACTIONS, PermissionAction } from '@/types';

interface TemporaryPermission {
    name: string;
    is_active: boolean;
}

const PermissionCheckbox = ({
    checked,
    onChange,
    disabled,
}: {
    checked: boolean;
    onChange: (checked: boolean) => void;
    disabled?: boolean;
}) => (
    <Checkbox
        checked={checked}
        onChange={(e) => onChange(e.target.checked)}
        disabled={disabled}
        size="small"
    />
);

interface PermissionMatrixProps {
    role: Role;
    permissions: TemporaryPermission[];
    isSystemRole: boolean;
    isEditing: boolean;
    onPermissionToggle: (module: string, action: PermissionAction) => void;
}

const PermissionMatrix = ({
    role,
    permissions,
    isSystemRole,
    isEditing,
    onPermissionToggle,
}: PermissionMatrixProps) => {
    // Create a lookup for existing permissions
    const permissionLookup = new Map(
        permissions.map((p) => [p.name, p.is_active])
    );

    const hasPermission = (module: string, action: PermissionAction): boolean => {
        const key = `${module}.${action}`;
        return permissionLookup.get(key) ?? false;
    };

    // No blocking - Admin has full access to edit ALL roles (including system roles)
    // isSystemRole is only used to prevent deletion, not editing

    return (
        <TableContainer>
            <Table size="small">
                <TableHead>
                    <TableRow>
                        <TableCell>Modul / Module</TableCell>
                        {PERMISSION_ACTIONS.map((action) => (
                            <TableCell key={action.key} align="center" sx={{ minWidth: 80 }}>
                                {action.label}
                            </TableCell>
                        ))}
                    </TableRow>
                </TableHead>
                <TableBody>
                    {PERMISSION_MODULES.map((module) => (
                        <TableRow key={module.key}>
                            <TableCell>
                                <Typography variant="body2">{module.label}</Typography>
                            </TableCell>
                            {PERMISSION_ACTIONS.map((action) => (
                                <TableCell key={action.key} align="center">
                                    <PermissionCheckbox
                                        checked={hasPermission(module.key, action.key)}
                                        onChange={() =>
                                            isEditing &&
                                            onPermissionToggle(module.key, action.key)
                                        }
                                        disabled={!isEditing}
                                    />
                                </TableCell>
                            ))}
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </TableContainer>
    );
};

interface RoleCardProps {
    role: Role;
    onEdit: () => void;
    onEditCancel: () => void;
    onSave: () => void;
    isEditing: boolean;
    isSystemRole: boolean;
    permissions: TemporaryPermission[];
    onPermissionToggle: (module: string, action: PermissionAction) => void;
}

const RoleCard = ({
    role,
    onEdit,
    onEditCancel,
    onSave,
    isEditing,
    isSystemRole,
    permissions,
    onPermissionToggle,
}: RoleCardProps) => (
    <Card sx={{ mb: 2 }}>
        <CardHeader
                            title={
                                <Box display="flex" alignItems="center" justifyContent="space-between">
                                    <Typography variant="h6">{role.name}</Typography>
                                </Box>
                            }
                            subheader={role.description}
                            action={
                                <Box display="flex" gap={1}>
                                    {isEditing ? (
                                        <>
                                            <Button
                                                size="small"
                                                onClick={onEditCancel}
                                            >
                                                Batal / Cancel
                                            </Button>
                                            <Button
                                                size="small"
                                                variant="contained"
                                                startIcon={<SaveIcon />}
                                                onClick={onSave}
                                            >
                                                Simpan
                                            </Button>
                                        </>
                                    ) : (
                                        <Button size="small" variant="outlined" onClick={onEdit}>
                                            Edit
                                        </Button>
                                    )}
                                </Box>
                            }
        />
        <CardContent>
            <Typography variant="body2" color="text.secondary" gutterBottom>
                {role.permissions?.length || 0} permissions / {role.is_active ? 'Aktif' : 'Tidak Aktif'}
            </Typography>
            <PermissionMatrix
                role={role}
                permissions={permissions}
                isSystemRole={isSystemRole}
                isEditing={isEditing}
                onPermissionToggle={onPermissionToggle}
            />
        </CardContent>
    </Card>
);

const CreateRoleDialog = ({
    open,
    onClose,
    onConfirm,
}: {
    open: boolean;
    onClose: () => void;
    onConfirm: (data: { name: string; description: string }) => void;
}) => {
    const [name, setName] = useState('');
    const [description, setDescription] = useState('');

    const handleConfirm = () => {
        if (name.trim()) {
            onConfirm({ name: name.trim(), description: description.trim() });
            setName('');
            setDescription('');
        }
    };

    return (
        <Dialog open={open} onClose={onClose} maxWidth="sm" fullWidth>
            <DialogTitle>Cipta Peranan Baru / Create New Role</DialogTitle>
            <DialogContent>
                <Box display="flex" flexDirection="column" gap={2} pt={2}>
                    <TextField
                        label="Nama / Name"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                        fullWidth
                        autoFocus
                    />
                    <TextField
                        label="Penerangan / Description"
                        value={description}
                        onChange={(e) => setDescription(e.target.value)}
                        fullWidth
                        multiline
                        rows={3}
                    />
                </Box>
            </DialogContent>
            <DialogActions>
                <Button onClick={onClose}>Batal / Cancel</Button>
                <Button onClick={handleConfirm} variant="contained" disabled={!name.trim()}>
                    Cipta / Create
                </Button>
            </DialogActions>
        </Dialog>
    );
};

export const PermissionList = () => {
    const { data: identity } = useGetIdentity();
    const isAdmin = identity?.role?.toLowerCase() === 'admin';
    const notify = useNotify();
    const refresh = useRefresh();
    const dataProvider = useDataProvider();

    // Fetch roles with permissions using useGetList
    const { data: roles = [], isLoading, refetch } = useGetList<Role>('roles', {
        pagination: { page: 1, perPage: 100 },
        sort: { field: 'id', order: 'ASC' },
    });

    const [editingRole, setEditingRole] = useState<number | null>(null);
    const [createDialogOpen, setCreateDialogOpen] = useState(false);
    const [tempPermissions, setTempPermissions] = useState<Record<number, TemporaryPermission[]>>({});

    const isSystemRole = (role: Role) =>
        role.name === 'Admin' || role.name === 'Staff' || role.name === 'Viewer';

    const handleEdit = (roleId: number) => {
        // Load current permissions into state
        const role = roles.find((r) => r.id === roleId);

        // Create a complete permissions list for this role (including unchecked ones)
        if (role) {
            const existingPermissions = role.permissions || [];
            const existingPermNames = new Set(existingPermissions.map((p) => p.name));

            // Generate all possible permissions for this role
            const completePermissions: TemporaryPermission[] = [];
            for (const module of PERMISSION_MODULES) {
                for (const action of PERMISSION_ACTIONS) {
                    const permName = `${module.key}.${action.key}`;
                    const existing = existingPermissions.find((p) => p.name === permName);
                    completePermissions.push({
                        name: permName,
                        is_active: existing ? existing.is_active : false,
                    });
                }
            }

            setTempPermissions((prev) => ({ ...prev, [roleId]: completePermissions }));
        }

        setEditingRole(roleId);
    };

    const handleEditCancel = (roleId: number) => {
        // Clear temp permissions for this role and exit edit mode
        setTempPermissions((prev) => {
            const { [roleId]: _, ...rest } = prev;
            return rest;
        });
        setEditingRole(null);
    };

    const handlePermissionToggle = (roleId: number, module: string, action: PermissionAction) => {
        setTempPermissions((prev) => {
            const rolePerms = prev[roleId] || [];
            const permName = `${module}.${action}`;
            const existing = rolePerms.find((p) => p.name === permName);

            let updated: TemporaryPermission[];
            if (existing) {
                updated = rolePerms.map((p) =>
                    p.name === permName ? { ...p, is_active: !p.is_active } : p
                );
            } else {
                updated = [...rolePerms, { name: permName, is_active: true }];
            }

            return { ...prev, [roleId]: updated };
        });
    };

    const handleSave = async (roleId: number) => {
        const role = roles.find((r) => r.id === roleId);
        const newPermissions = tempPermissions[roleId] || [];

        if (!role) return;

        try {
            // Get list of permission names that should be active
            const activePermissionNames = newPermissions
                .filter((p) => p.is_active)
                .map((p) => p.name);

            // Get existing permission names
            const existingPermissions = role.permissions || [];

            // 1. Delete permissions that are no longer active
            for (const existing of existingPermissions) {
                if (!activePermissionNames.includes(existing.name)) {
                    await dataProvider.delete('permissions', { id: existing.id, previousData: existing });
                }
            }

            // 2. Create or ensure permissions that should be active
            for (const permName of activePermissionNames) {
                const existing = existingPermissions.find((p) => p.name === permName);
                if (existing) {
                    // Permission exists, ensure it's active
                    if (!existing.is_active) {
                        await dataProvider.update('permissions', {
                            id: existing.id,
                            data: { is_active: true },
                            previousData: existing,
                        });
                    }
                } else {
                    // Permission doesn't exist, create it
                    await dataProvider.create('permissions', {
                        data: {
                            role_id: roleId,
                            name: permName,
                            description: permName,
                            is_active: true,
                        },
                    });
                }
            }

            notify('Kebenaran berjaya dikemaskini / Permissions updated successfully', { type: 'success' });
            refetch();
            refresh();
            setEditingRole(null);
            setTempPermissions((prev) => ({ ...prev, [roleId]: [] }));
        } catch (error) {
            notify('Ralat mengemaskini kebenaran / Error updating permissions', { type: 'error' });
            console.error(error);
        }
    };

    const handleCreateRole = async (data: { name: string; description: string }) => {
        try {
            await dataProvider.create('roles', { data });
            notify('Peranan berjaya dicipta / Role created successfully', { type: 'success' });
            refetch();
            setCreateDialogOpen(false);
        } catch (error) {
            notify('Ralat mencipta peranan / Error creating role', { type: 'error' });
            console.error(error);
        }
    };

    if (!isAdmin) {
        return (
            <Box p={2}>
                <Typography color="error">
                    Anda tidak mempunyai akses ke halaman ini / You do not have access to this page.
                </Typography>
            </Box>
        );
    }

    if (isLoading) {
        return <Typography>Loading... / Memuatkan...</Typography>;
    }

    // Ensure each role has complete permissions for display
    const getDisplayPermissions = (role: Role, isEditingForThisRole: boolean): TemporaryPermission[] => {
        // If editing this role, use tempPermissions
        const tempPerms = tempPermissions[role.id];
        if (isEditingForThisRole && tempPerms) {
            return tempPerms;
        }

        // Otherwise, build complete permissions from role.permissions
        const existingPermissions = role.permissions || [];
        const existingPermNames = new Set(existingPermissions.map((p) => p.name));

        const completePermissions: TemporaryPermission[] = [];
        for (const module of PERMISSION_MODULES) {
            for (const action of PERMISSION_ACTIONS) {
                const permName = `${module.key}.${action.key}`;
                const existing = existingPermissions.find((p) => p.name === permName);
                completePermissions.push({
                    name: permName,
                    is_active: existing ? existing.is_active : false,
                });
            }
        }

        return completePermissions;
    };

    return (
        <Box p={2}>
            <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
                <Typography variant="h5">Urusan Kebenaran / Permissions Management</Typography>
                <Button
                    variant="contained"
                    startIcon={<AddIcon />}
                    onClick={() => setCreateDialogOpen(true)}
                >
                    Cipta Peranan / Create Role
                </Button>
            </Box>

            {roles.map((role) => (
                <RoleCard
                    key={role.id}
                    role={role}
                    isSystemRole={isSystemRole(role)}
                    isEditing={editingRole === role.id}
                    permissions={getDisplayPermissions(role, editingRole === role.id)}
                    onEdit={() => handleEdit(role.id)}
                    onEditCancel={() => handleEditCancel(role.id)}
                    onSave={() => handleSave(role.id)}
                    onPermissionToggle={(module, action) => handlePermissionToggle(role.id, module, action)}
                />
            ))}

            <CreateRoleDialog
                open={createDialogOpen}
                onClose={() => setCreateDialogOpen(false)}
                onConfirm={handleCreateRole}
            />
        </Box>
    );
};
