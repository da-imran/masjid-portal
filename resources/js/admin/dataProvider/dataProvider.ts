import {
    DataProvider,
    fetchUtils,
    GetListParams,
    GetOneParams,
    GetManyParams,
    GetManyReferenceParams,
    CreateParams,
    UpdateParams,
    UpdateManyParams,
    DeleteParams,
    DeleteManyParams,
} from 'react-admin';

const apiUrl = '/api/v1/admin';

const getToken = () => {
    return localStorage.getItem('token');
};

const formatFilters = (filters: any): Record<string, string> => {
    const params: Record<string, string> = {};

    Object.keys(filters).forEach((key) => {
        const value = filters[key];

        if (typeof value === 'object' && value !== null) {
            if (value.id !== undefined) {
                params[key] = String(value.id);
            } else if (value.startsWith) {
                params[key] = String(value);
            }
        } else if (value !== null && value !== undefined) {
            params[key] = String(value);
        }
    });

    return params;
};

export const dataProvider: DataProvider = {
    getList: async (resource: string, params: GetListParams) => {
        const { page = 1, perPage = 10 } = params.pagination || {};
        const { field = 'id', order = 'ASC' } = params.sort || {};

        const query: Record<string, string> = {
            page: String(page),
            per_page: String(perPage),
            sort_by: field,
            sort_order: order,
            ...formatFilters(params.filter),
        };

        const url = `${apiUrl}/${resource}?${new URLSearchParams(query)}`;

        const options = {
            headers: new Headers({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`,
            }),
        };

        try {
            const response = await fetchUtils.fetchJson(url, options);
            // Handle both nested data format and direct data format
            const jsonData = response.json;
            const data = jsonData?.data || jsonData;
            const meta = jsonData?.meta;

            return {
                data: Array.isArray(data) ? data : [],
                total: meta?.total || (Array.isArray(data) ? data.length : 0),
            };
        } catch (error: any) {
            throw new Error(error.message || 'Failed to fetch data');
        }
    },

    getOne: async (resource: string, params: GetOneParams) => {
        const url = `${apiUrl}/${resource}/${params.id}`;

        const options = {
            headers: new Headers({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`,
            }),
        };

        try {
            const response = await fetchUtils.fetchJson(url, options);
            // Handle both nested data format and direct data format
            const data = response.json?.data || response.json;
            return { data };
        } catch (error: any) {
            throw new Error(error.message || 'Failed to fetch record');
        }
    },

    getMany: async (resource: string, params: GetManyParams) => {
        const ids = params.ids.join(',');
        const url = `${apiUrl}/${resource}?ids=${ids}`;

        const options = {
            headers: new Headers({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`,
            }),
        };

        try {
            const response = await fetchUtils.fetchJson(url, options);
            // Handle both nested data format and direct data format
            const data = response.json?.data || response.json;
            return { data: Array.isArray(data) ? data : [] };
        } catch (error: any) {
            throw new Error(error.message || 'Failed to fetch records');
        }
    },

    getManyReference: async (resource: string, params: GetManyReferenceParams) => {
        const { page = 1, perPage = 10 } = params.pagination || {};
        const { field = 'id', order = 'ASC' } = params.sort || {};

        const query: Record<string, string> = {
            page: String(page),
            per_page: String(perPage),
            sort_by: field,
            sort_order: order,
            [params.target]: String(params.id),
            ...formatFilters(params.filter),
        };

        const url = `${apiUrl}/${resource}?${new URLSearchParams(query)}`;

        const options = {
            headers: new Headers({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`,
            }),
        };

        try {
            const response = await fetchUtils.fetchJson(url, options);
            // Handle both nested data format and direct data format
            const jsonData = response.json;
            const data = jsonData?.data || jsonData;
            const meta = jsonData?.meta;

            return {
                data: Array.isArray(data) ? data : [],
                total: meta?.total || (Array.isArray(data) ? data.length : 0),
            };
        } catch (error: any) {
            throw new Error(error.message || 'Failed to fetch data');
        }
    },

    create: async (resource: string, params: CreateParams) => {
        const url = `${apiUrl}/${resource}`;

        const options: RequestInit = {
            method: 'POST',
            headers: new Headers({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`,
            }),
            body: JSON.stringify(params.data),
        };

        try {
            const response = await fetchUtils.fetchJson(url, options);
            // Handle both nested data format and direct data format
            const data = response.json?.data || response.json;
            return { data };
        } catch (error: any) {
            throw new Error(error.message || 'Failed to create record');
        }
    },

    update: async (resource: string, params: UpdateParams) => {
        const url = `${apiUrl}/${resource}/${params.id}`;

        const options: RequestInit = {
            method: 'PUT',
            headers: new Headers({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`,
            }),
            body: JSON.stringify(params.data),
        };

        try {
            const response = await fetchUtils.fetchJson(url, options);
            // Handle both nested data format and direct data format
            const data = response.json?.data || response.json;
            return { data };
        } catch (error: any) {
            throw new Error(error.message || 'Failed to update record');
        }
    },

    updateMany: async (resource: string, params: UpdateManyParams) => {
        const url = `${apiUrl}/${resource}`;

        const options: RequestInit = {
            method: 'PUT',
            headers: new Headers({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`,
            }),
            body: JSON.stringify({ ids: params.ids, data: params.data }),
        };

        try {
            await fetchUtils.fetchJson(url, options);
            return { data: params.ids };
        } catch (error: any) {
            throw new Error(error.message || 'Failed to update records');
        }
    },

    delete: async (resource: string, params: DeleteParams) => {
        const url = `${apiUrl}/${resource}/${params.id}`;

        const options = {
            method: 'DELETE',
            headers: new Headers({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`,
            }),
        };

        try {
            await fetchUtils.fetchJson(url, options);
            return { data: { id: params.id } as any };
        } catch (error: any) {
            throw new Error(error.message || 'Failed to delete record');
        }
    },

    deleteMany: async (resource: string, params: DeleteManyParams) => {
        const url = `${apiUrl}/${resource}`;

        const options: RequestInit = {
            method: 'DELETE',
            headers: new Headers({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`,
            }),
            body: JSON.stringify({ ids: params.ids }),
        };

        try {
            await fetchUtils.fetchJson(url, options);
            return { data: params.ids };
        } catch (error: any) {
            throw new Error(error.message || 'Failed to delete records');
        }
    },
};
