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
            if (response.status !== 200) {
                const failedLogin = 'Failed to login';
                throw new Error(failedLogin);
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
            try {
                const request = new Request(`${apiUrl}/logout`, {
                    method: 'POST',
                    headers: new Headers({
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    }),
                });

                await fetch(request);
            } catch (error) {
                // Ignore logout API errors, just clear local storage
                console.error('Logout API error:', error);
            }
        }

        localStorage.removeItem('token');
        localStorage.removeItem('user');
        // Return a resolved promise to redirect to login
        return Promise.resolve();
    },

    checkAuth: () => {
        const token = localStorage.getItem('token');
        const user = localStorage.getItem('user');

        if (token && user) {
            return Promise.resolve();
        }
        return Promise.reject();
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
