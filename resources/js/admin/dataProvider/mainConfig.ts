import {
    DataProvider,
    GetListParams,
    GetOneParams,
    GetManyParams,
    GetManyReferenceParams,
    CreateParams,
    UpdateParams,
    UpdateManyParams,
    DeleteParams,
    DeleteManyParams,
    fetchUtils,
    RaRecord,
    DeleteResult,
    DeleteManyResult,
    UpdateManyResult,
} from 'react-admin';

export const mainConfig = (apiUrl: string, adminApiUrl: string): DataProvider => {
    const getToken = () => localStorage.getItem('token');
    const authHeaders = () => ({ Authorization: `Bearer ${getToken()}` });

    return {
        getList: async (_resource: string, params: GetListParams) => {
            const { page = 1, perPage = 10 } = params.pagination || {};
            const { field = 'id', order = 'ASC' } = params.sort || {};

            const query = {
                page: String(page),
                per_page: String(perPage),
                sort_by: field,
                sort_order: order,
                ...Object.fromEntries(
                    Object.entries(params.filter || {}).map(([k, v]) => [k, String(v)])
                ),
            };

            const url = `${apiUrl}?${new URLSearchParams(query)}`;
            const response = await fetchUtils.fetchJson(url, { headers: new Headers(authHeaders()) });
            const data = response.json?.data || response.json || [];
            const total = response.json?.meta?.total || data.length;
            return { data, total };
        },

        getOne: async (_resource: string, params: GetOneParams) => {
            const url = `${apiUrl}/${params.id}`;
            const response = await fetchUtils.fetchJson(url, { headers: new Headers(authHeaders()) });
            return { data: response.json?.data || response.json };
        },

        getMany: async (_resource: string, params: GetManyParams) => {
            const url = `${apiUrl}?ids=${params.ids.join(',')}`;
            const response = await fetchUtils.fetchJson(url, { headers: new Headers(authHeaders()) });
            return { data: response.json?.data || response.json || [] };
        },

        getManyReference: async (_resource: string, params: GetManyReferenceParams) => {
            const { page = 1, perPage = 10 } = params.pagination || {};
            const { field = 'id', order = 'ASC' } = params.sort || {};

            const query = {
                page: String(page),
                per_page: String(perPage),
                sort_by: field,
                sort_order: order,
                [params.target]: String(params.id),
                ...Object.fromEntries(Object.entries(params.filter || {}).map(([k, v]) => [k, String(v)])),
            };

            const url = `${apiUrl}?${new URLSearchParams(query)}`;
            const response = await fetchUtils.fetchJson(url, { headers: new Headers(authHeaders()) });
            const data = response.json?.data || response.json || [];
            const total = response.json?.meta?.total || data.length;
            return { data, total };
        },

        create: async (_resource: string, params: CreateParams) => {
            const hasFile = Object.values(params.data).some(v => v instanceof File);

            let body: string | FormData;
            const headers = new Headers(authHeaders());

            if (hasFile) {
                const formData = new FormData();
                Object.entries(params.data).forEach(([k, v]) =>
                    v instanceof File ? formData.append(k, v) : formData.append(k, String(v))
                );
                body = formData;
            } else {
                body = JSON.stringify(params.data);
                headers.set('Content-Type', 'application/json');
            }

            const response = await fetch(adminApiUrl, {
                method: 'POST',
                headers,
                body,
            });

            const status = response.status;
            let json: any = null;

            try {
                json = await response.json();
            } catch {
                // JSON parsing failed
            }

            // Handle validation errors (422)
            if (status === 422) {
                const errors: Record<string, string[]> = {};
                if (json?.errors) {
                    Object.entries(json.errors).forEach(([key, messages]) => {
                        errors[key] = Array.isArray(messages) ? messages : [messages as string];
                    });
                }
                // Extract the first error message from validation errors
                const errorMessage = Object.values(errors)[0]?.[0]
                    || json?.message
                    || 'Validation failed';
                throw new Error(errorMessage);
            }

            // Handle other errors
            if (!response.ok) {
                throw new Error(json?.message || `Request failed with status ${status}`);
            }

            // Ensure we return proper data format for react-admin
            if (!json) {
                throw new Error('Invalid response');
            }

            // Handle both { data: ... } and { message: ..., data: ... } formats
            const responseData = json.data !== undefined ? json.data : json;

            return { data: responseData };
        },

        update: async (_resource: string, params: UpdateParams) => {
            const url = `${adminApiUrl}/${params.id}`;
            const hasFile = Object.values(params.data).some(v => v instanceof File);

            let body: string | FormData;
            const headers = new Headers(authHeaders());

            if (hasFile) {
                const formData = new FormData();
                formData.append('_method', 'PUT');
                Object.entries(params.data).forEach(([k, v]) =>
                    v instanceof File ? formData.append(k, v) : formData.append(k, String(v))
                );
                body = formData;
            } else {
                body = JSON.stringify(params.data);
                headers.set('Content-Type', 'application/json');
            }

            const response = await fetch(url, {
                method: hasFile ? 'POST' : 'PUT',
                headers,
                body,
            });

            const status = response.status;
            let json: any = null;

            try {
                json = await response.json();
            } catch {
                // JSON parsing failed
            }

            // Handle validation errors (422)
            if (status === 422) {
                const errors: Record<string, string[]> = {};
                if (json?.errors) {
                    Object.entries(json.errors).forEach(([key, messages]) => {
                        errors[key] = Array.isArray(messages) ? messages : [messages as string];
                    });
                }
                // Extract the first error message from validation errors
                const errorMessage = Object.values(errors)[0]?.[0]
                    || json?.message
                    || 'Validation failed';
                throw new Error(errorMessage);
            }

            // Handle other errors
            if (!response.ok) {
                throw new Error(json?.message || `Request failed with status ${status}`);
            }

            // Ensure we return proper data format for react-admin
            if (!json) {
                throw new Error('Invalid response');
            }

            const responseData = json.data !== undefined ? json.data : json;

            return { data: responseData };
        },

        updateMany: async <RecordType extends RaRecord = any>(
            _resource: string,
            params: UpdateManyParams<RecordType>
        ): Promise<UpdateManyResult<RecordType>> => {
            await fetchUtils.fetchJson(adminApiUrl, {
                method: 'PUT',
                headers: new Headers(authHeaders()),
                body: JSON.stringify({ ids: params.ids, data: params.data }),
            });

            return { data: params.ids };
        },

        delete: async <RecordType extends RaRecord = any>(
            _resource: string,
            params: DeleteParams<RecordType>
        ): Promise<DeleteResult<RecordType>> => {
            const url = `${adminApiUrl}/${params.id}`;
            const headers = new Headers(authHeaders());

            const response = await fetch(url, {
                method: 'DELETE',
                headers,
            });

            const status = response.status;
            let json: any = null;

            try {
                json = await response.json();
            } catch {
                // JSON parsing failed
            }

            // Handle errors with proper message
            if (!response.ok) {
                throw new Error(json?.message || `Request failed with status ${status}`);
            }

            return { data: { id: params.id } as RecordType };
        },

        deleteMany: async <RecordType extends RaRecord = any>(
            _resource: string,
            params: DeleteManyParams<RecordType>
        ): Promise<DeleteManyResult<RecordType>> => {
            const headers = new Headers(authHeaders());

            const response = await fetch(adminApiUrl, {
                method: 'DELETE',
                headers,
                body: JSON.stringify({ ids: params.ids }),
            });

            const status = response.status;
            let json: any = null;

            try {
                json = await response.json();
            } catch {
                // JSON parsing failed
            }

            // Handle errors with proper message
            if (!response.ok) {
                throw new Error(json?.message || `Request failed with status ${status}`);
            }

            return { data: params.ids };
        },
    };
};
