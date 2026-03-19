import { AuthProvider } from 'react-admin';

const API_URL = import.meta.env.VITE_API_URL;

// Session timeout configuration (in milliseconds)
const SESSION_TIMEOUT = 30 * 60 * 1000; // 30 minutes
const WARNING_TIME = 5 * 60 * 1000; // 5 minutes before timeout

// Session state
let sessionTimeout: NodeJS.Timeout | null = null;
let warningTimeout: NodeJS.Timeout | null = null;
let lastActivity = Date.now();

// Reset activity timer
const resetActivityTimer = () => {
    lastActivity = Date.now();
};

// Clear session timers
const clearSessionTimers = () => {
    if (sessionTimeout) {
        clearTimeout(sessionTimeout);
        sessionTimeout = null;
    }
    if (warningTimeout) {
        clearTimeout(warningTimeout);
        warningTimeout = null;
    }
};

// Initialize session timeout
const initSessionTimeout = (logoutCallback: () => void) => {
    clearSessionTimers();

    // Warning timeout (5 minutes before session expires)
    warningTimeout = setTimeout(() => {
        console.warn('Session will expire in 5 minutes due to inactivity');
        // You could show a toast notification here
    }, SESSION_TIMEOUT - WARNING_TIME);

    // Session timeout
    sessionTimeout = setTimeout(() => {
        console.warn('Session expired due to inactivity');
        logoutCallback();
    }, SESSION_TIMEOUT);
};

// Setup activity listeners
const setupActivityListeners = (logoutCallback: () => void) => {
    const events = ['mousedown', 'keydown', 'scroll', 'touchstart', 'click'];

    const handleActivity = () => {
        const timeSinceActivity = Date.now() - lastActivity;

        // If user was inactive for more than SESSION_TIMEOUT, logout
        if (timeSinceActivity >= SESSION_TIMEOUT) {
            logoutCallback();
            return;
        }

        resetActivityTimer();
        initSessionTimeout(logoutCallback);
    };

    events.forEach(event => {
        document.addEventListener(event, handleActivity, { passive: true });
    });

    return () => {
        events.forEach(event => {
            document.removeEventListener(event, handleActivity);
        });
    };
};

// Auto-logout function
const performLogout = async () => {
    clearSessionTimers();
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    localStorage.removeItem('lastActivity');
    window.location.href = '/#/login';
};

export const authProvider: AuthProvider = {
    login: async ({ username, password }) => {
        const request = new Request('api/login', {
            method: 'POST',
            body: JSON.stringify({ email: username, password }),
            headers: new Headers({
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }),
        });

        try {
            const response = await fetch(request);
            console.log('SINI', response);
            if (response.status !== 200) {
                const failedLogin = 'Failed to login';
                throw new Error(failedLogin);
            }
            const auth = await response.json();

            // Check if user has admin access
            if (!auth.user?.role || (auth.user.role.name !== 'Admin' && auth.user.role.name !== 'Staff')) {
                // User doesn't have admin access
                return Promise.reject(new Error('Anda tidak mempunyai akses ke admin panel / You do not have access to admin panel'));
            }

            // Store token
            localStorage.setItem('token', auth.token);

            // Store user data
            localStorage.setItem('user', JSON.stringify(auth.user));

            // Initialize session tracking
            const now = Date.now();
            localStorage.setItem('lastActivity', String(now));
            lastActivity = now;

            // Setup session timeout
            resetActivityTimer();
            initSessionTimeout(performLogout);
            setupActivityListeners(performLogout);

            return Promise.resolve();
        } catch (error) {
            return Promise.reject(error);
        }
    },

    logout: async () => {
        const token = localStorage.getItem('token');

        if (token) {
            try {
                const request = new Request('api/logout', {
                    method: 'POST',
                    headers: new Headers({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    }),
                });

                await fetch(request);
            } catch (error) {
                // Ignore logout API errors, just clear local storage
                console.error('Logout API error:', error);
            }
        }

        clearSessionTimers();
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('lastActivity');

        // Return a resolved promise to redirect to login
        return Promise.resolve();
    },

    checkAuth: () => {
        const token = localStorage.getItem('token');
        const userStr = localStorage.getItem('user');
        const lastActivityStr = localStorage.getItem('lastActivity');

        if (token && userStr) {
            const user = JSON.parse(userStr);

            // Check if user has admin access - redirect to public if not
            if (!user?.role || (user.role.name !== 'Admin' && user.role.name !== 'Staff')) {
                // Non-admin user trying to access admin panel - redirect to public site
                window.location.href = '/#/login';
                return Promise.reject();
            }

            // Check if session has expired
            if (lastActivityStr) {
                const lastActivityTime = parseInt(lastActivityStr, 10);
                const timeSinceActivity = Date.now() - lastActivityTime;

                if (timeSinceActivity >= SESSION_TIMEOUT) {
                    // Session expired, logout
                    performLogout();
                    return Promise.reject();
                }

                // Update last activity time
                lastActivity = Date.now();
                localStorage.setItem('lastActivity', String(lastActivity));
                resetActivityTimer();
                initSessionTimeout(performLogout);
            }

            return Promise.resolve();
        }
        return Promise.reject();
    },

    checkError: (error) => {
        const status = error.status;
        if (status === 401 || status === 403) {
            clearSessionTimers();
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            localStorage.removeItem('lastActivity');
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
                role: user.role?.name,
            });
        }
        return Promise.reject();
    },
};
