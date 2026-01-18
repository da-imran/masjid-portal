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
            // Re-throw the error so react-admin can handle it via authProvider.checkError
            throw error;
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
            throw error;
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
            throw error;
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
            // Re-throw the error so react-admin can handle it via authProvider.checkError
            throw error;
        }
    },

    create: async (resource: string, params: CreateParams) => {
        const url = `${apiUrl}/${resource}`;
        const token = getToken();

        // Check if params.data contains any File objects (image uploads)
        const hasFile = Object.values(params.data).some(
            value => value instanceof File || (value && typeof value === 'object' && 'rawFile' in value)
        );

        let body: string | FormData;
        let headers: Headers;

        if (hasFile) {
            // Use FormData for file uploads
            const formData = new FormData();
            Object.entries(params.data).forEach(([key, value]) => {
                if (value instanceof File) {
                    formData.append(key, value);
                } else if (value && typeof value === 'object' && 'rawFile' in value) {
                    // React Admin ImageInput format
                    formData.append(key, (value as any).rawFile);
                } else if (value !== null && value !== undefined) {
                    formData.append(key, String(value));
                }
            });
            body = formData;
            // Don't set Content-Type when using FormData - browser will set it with boundary
            headers = new Headers({
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            });
        } else {
            headers = new Headers({
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            });
            body = JSON.stringify(params.data);
        }

        const options: RequestInit = {
            method: 'POST',
            headers,
            body,
        };

        try {
            const response = await fetchUtils.fetchJson(url, options);

            // fetchUtils.fetchJson returns { status, headers, body, json }
            const jsonData = response.json;

            // Handle both nested data format and direct data format
            const data = jsonData?.data || jsonData;

            // Ensure data has an id property (required by react-admin)
            if (!data || typeof data !== 'object' || !('id' in data)) {
                throw new Error('Response data must contain an id field');
            }

            return { data };
        } catch (error: any) {
            // Handle fetchUtils errors which may have status and body properties
            if (error.status) {
                let errorMessages = error.message || 'Failed to create record';

                if (error.body) {
                    try {
                        const errorBody = typeof error.body === 'string'
                            ? JSON.parse(error.body)
                            : error.body;

                        // Laravel validation errors format
                        if (errorBody.errors || errorBody.message) {
                            const validationErrors = errorBody.errors
                                ? Object.values(errorBody.errors).flat().join(', ')
                                : errorBody.message;
                            errorMessages = validationErrors;
                        }
                    } catch (e) {
                        // If parsing fails, use original message
                    }
                }

                // Create a new error with the enhanced message but keep original properties
                const enhancedError = new Error(errorMessages) as any;
                enhancedError.status = error.status;
                enhancedError.body = error.body;
                throw enhancedError;
            }

            throw error;
        }
    },

    update: async (resource: string, params: UpdateParams) => {
        const url = `${apiUrl}/${resource}/${params.id}`;
        const token = getToken();

        // Check if params.data contains any File objects (image uploads)
        const hasFile = Object.values(params.data).some(
            value => value instanceof File || (value && typeof value === 'object' && 'rawFile' in value)
        );

        let body: string | FormData;
        let headers: Headers;

        if (hasFile) {
            // Use FormData for file uploads
            const formData = new FormData();
            // Use POST method with _method=PUT for file uploads in Laravel
            formData.append('_method', 'PUT');
            Object.entries(params.data).forEach(([key, value]) => {
                if (value instanceof File) {
                    formData.append(key, value);
                } else if (value && typeof value === 'object' && 'rawFile' in value) {
                    // React Admin ImageInput format
                    formData.append(key, (value as any).rawFile);
                } else if (value !== null && value !== undefined) {
                    formData.append(key, String(value));
                }
            });
            body = formData;
            // Don't set Content-Type when using FormData
            headers = new Headers({
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            });
        } else {
            headers = new Headers({
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            });
            body = JSON.stringify(params.data);
        }

        const options: RequestInit = {
            method: hasFile ? 'POST' : 'PUT',
            headers,
            body,
        };

        try {
            const response = await fetchUtils.fetchJson(url, options);
            // Handle both nested data format and direct data format
            const data = response.json?.data || response.json;
            return { data };
        } catch (error: any) {
            throw error;
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
            throw error;
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
            throw error;
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
            throw error;
        }
    },
};
