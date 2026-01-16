import { AuthProvider } from 'react-admin';

const apiUrl = '/api/v1/admin';

export const authProvider: AuthProvider = {
    login: async ({ username, password }) => {
        const request = new Request(`${apiUrl}/login`, {
            method: 'POST',
            body: JSON.stringify({ email: username, password }),
            headers: new Headers({ 'Content-Type': 'application/json' }),
        });

        try {
            const response = await fetch(request);
            if (response.status < 200 || response.status >= 300) {
                throw new Error(response.statusText);
            }
            const auth = await response.json();

            // Store token
            localStorage.setItem('token', auth.token);

            // Store user data
            localStorage.setItem('user', JSON.stringify(auth.user));

            return Promise.resolve();
        } catch (error) {
            return Promise.reject(error);
        }
    },

    logout: async () => {
        const token = localStorage.getItem('token');

        if (token) {
            const request = new Request(`${apiUrl}/logout`, {
                method: 'POST',
                headers: new Headers({
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                }),
            });

            await fetch(request);
        }

        localStorage.removeItem('token');
        localStorage.removeItem('user');
        return Promise.resolve();
    },

    checkAuth: () => {
        return localStorage.getItem('token')
            ? Promise.resolve()
            : Promise.reject();
    },

    checkError: (error) => {
        const status = error.status;
        if (status === 401 || status === 403) {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            return Promise.reject();
        }
        return Promise.resolve();
    },

    getPermissions: async () => {
        const userStr = localStorage.getItem('user');
        if (userStr) {
            const user = JSON.parse(userStr);
            return Promise.resolve(user.permissions || []);
        }
        return Promise.reject();
    },

    getIdentity: async () => {
        const userStr = localStorage.getItem('user');
        if (userStr) {
            const user = JSON.parse(userStr);
            return Promise.resolve({
                id: user.id,
                fullName: user.name,
                avatar: undefined,
                role: user.role?.slug,
            });
        }
        return Promise.reject();
    },
};
