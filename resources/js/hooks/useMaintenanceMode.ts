import { useState, useEffect } from 'react';
import api from '@/lib/api';
import type { MaintenanceStatus } from '@/types';

/**
 * Custom hook to check if the application is in maintenance mode.
 * Polls the maintenance endpoint every 30 seconds.
 *
 * @returns {MaintenanceStatus} Object containing maintenance status and message
 */
export const useMaintenanceMode = (): MaintenanceStatus & { loading: boolean } => {
    const [status, setStatus] = useState<MaintenanceStatus & { loading: boolean }>({
        maintenance: false,
        message: null,
        loading: true
    });

    useEffect(() => {
        const checkMaintenance = async () => {
            try {
                const response = await api.get<MaintenanceStatus>('/maintenance');
                setStatus({
                    maintenance: response.data.maintenance,
                    message: response.data.message,
                    loading: false
                });
            } catch (err) {
                // If the request fails, assume the app is not in maintenance mode
                // This prevents showing maintenance page due to network issues
                console.error('Failed to check maintenance status:', err);
                setStatus({
                    maintenance: false,
                    message: null,
                    loading: false
                });
            }
        };

        // Check immediately on mount
        checkMaintenance();

        // Poll every 30 seconds
        const interval = setInterval(checkMaintenance, 30000);

        return () => clearInterval(interval);
    }, []);

    return status;
};
