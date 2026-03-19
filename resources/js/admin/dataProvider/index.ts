import { DataProvider } from 'react-admin';
import { mainConfig } from './mainConfig.ts';

// Public API modules (no /admin prefix, except for write operations)
const PUBLIC_MODULES = [
    'berita',
    'kemudahan',
    'takwim',
];

// Admin-only modules (use /admin prefix for all operations)
const ADMIN_MODULES = [
    'users',
    'roles',
    'permissions',
];

// Helper to build API URLs for each module
const buildProvider = (module: string, useAdminPrefix: boolean) => {
    const API_URL = import.meta.env.VITE_API_URL + (useAdminPrefix ? '/admin/' : '/') + module;
    const ADMIN_API_URL = import.meta.env.VITE_API_ADMIN_URL + '/' + module;
    return mainConfig(API_URL, ADMIN_API_URL);
};

// Build providers for each module category
const providers: Record<string, DataProvider> = {
    ...PUBLIC_MODULES.reduce((acc, module) => {
        acc[module] = buildProvider(module, false);
        return acc;
    }, {} as Record<string, DataProvider>),
    ...ADMIN_MODULES.reduce((acc, module) => {
        acc[module] = buildProvider(module, true);
        return acc;
    }, {} as Record<string, DataProvider>),
};

// Unified wrapper to forward all calls
export const dataProvider: DataProvider = {
    getList: (resource, params) => {
        // Permissions resource should fetch from roles endpoint with permissions included
        if (resource === 'permissions') {
            return providers['roles'].getList('roles', params);
        }
        return providers[resource].getList(resource, params);
    },
    getOne: (resource, params) => {
        if (resource === 'permissions') {
            return providers['roles'].getOne('roles', params);
        }
        return providers[resource].getOne(resource, params);
    },
    getMany: (resource, params) => providers[resource].getMany(resource, params),
    getManyReference: (resource, params) => providers[resource].getManyReference(resource, params),
    create: (resource, params) => {
        // For permissions, use the permissions endpoint; for roles, use the roles endpoint
        if (resource === 'permissions') {
            return providers['permissions'].create(resource, params);
        }
        return providers[resource].create(resource, params);
    },
    update: (resource, params) => {
        if (resource === 'permissions') {
            return providers['permissions'].update(resource, params);
        }
        return providers[resource].update(resource, params);
    },
    updateMany: (resource, params) => providers[resource].updateMany(resource, params),
    delete: (resource, params) => {
        // For permissions, use permissions endpoint; for roles, use roles endpoint
        if (resource === 'permissions') {
            return providers['permissions'].delete(resource, params);
        }
        return providers[resource].delete(resource, params);
    },
    deleteMany: (resource, params) => providers[resource].deleteMany(resource, params),
};
