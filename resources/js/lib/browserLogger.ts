/**
 * Browser Logger Utility
 *
 * Captures console logs, errors, and warnings from the React application
 * and stores them in localStorage for debugging without using API calls.
 *
 * Logs can be viewed in the browser's Developer Tools Console or Application > Local Storage.
 */

interface LogEntry {
    level: 'debug' | 'info' | 'warn' | 'error';
    message: string;
    timestamp: number;
    url?: string;
    userId?: number | null;
    stack?: string;
    data?: unknown;
}

const STORAGE_KEY = 'browser_logs';
const MAX_LOGS = 500; // Maximum number of logs to keep in localStorage

class BrowserLogger {
    private isDevelopment = import.meta.env.MODE === 'development';
    private originalConsole = {
        log: console.log,
        info: console.info,
        warn: console.warn,
        error: console.error,
    };

    constructor() {
        this.initialize();
    }

    private initialize(): void {
        // Override console methods
        this.overrideConsole();

        // Set up global error handler
        this.setupGlobalErrorHandlers();

        // Clean old logs on initialization (keep last 500)
        this.cleanOldLogs();
    }

    private overrideConsole(): void {
        console.log = (...args: unknown[]) => {
            this.originalConsole.log(...args);
            if (!this.isDevelopment) {
                this.storeLog('info', this.formatMessage(args), this.extractData(args));
            }
        };

        console.info = (...args: unknown[]) => {
            this.originalConsole.info(...args);
            if (!this.isDevelopment) {
                this.storeLog('info', this.formatMessage(args), this.extractData(args));
            }
        };

        console.warn = (...args: unknown[]) => {
            this.originalConsole.warn(...args);
            this.storeLog('warn', this.formatMessage(args), this.extractData(args));
        };

        console.error = (...args: unknown[]) => {
            this.originalConsole.error(...args);
            this.storeLog('error', this.formatMessage(args), this.extractData(args));
        };
    }

    private setupGlobalErrorHandlers(): void {
        // Handle uncaught errors
        window.addEventListener('error', (event) => {
            this.storeLog('error', event.message, {
                type: 'uncaughtError',
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                stack: event.error?.stack,
            });
        });

        // Handle unhandled promise rejections
        window.addEventListener('unhandledrejection', (event) => {
            this.storeLog('error', 'Unhandled Promise Rejection', {
                reason: event.reason,
                stack: event.reason?.stack,
            });
        });
    }

    private formatMessage(args: unknown[]): string {
        return args
            .map((arg) => {
                if (typeof arg === 'string') return arg;
                if (arg instanceof Error) return arg.message;
                try {
                    return JSON.stringify(arg);
                } catch {
                    return String(arg);
                }
            })
            .join(' ');
    }

    private extractData(args: unknown[]): unknown | undefined {
        // Only include detailed data for errors or if explicitly requested
        const hasError = args.some((arg) => arg instanceof Error);
        if (!hasError && args.length <= 1) return undefined;

        try {
            return args.map((arg) => {
                if (arg instanceof Error) {
                    return {
                        message: arg.message,
                        stack: arg.stack,
                        name: arg.name,
                    };
                }
                return arg;
            });
        } catch {
            return undefined;
        }
    }

    private storeLog(level: LogEntry['level'], message: string, data?: unknown): void {
        const entry: LogEntry = {
            level,
            message,
            timestamp: Date.now(),
            url: window.location.href,
            userId: this.getUserId(),
            data,
        };

        // Extract stack trace if it's an error
        if (data && typeof data === 'object' && 'stack' in data) {
            entry.stack = String(data.stack);
        }

        try {
            // Get existing logs
            const existingLogs = this.getLogs();
            const newLogs = [...existingLogs, entry];

            // Keep only the most recent logs
            const trimmedLogs = newLogs.slice(-MAX_LOGS);

            // Store to localStorage
            localStorage.setItem(STORAGE_KEY, JSON.stringify(trimmedLogs));
        } catch (error) {
            // If localStorage is full or unavailable, fail silently
            // This prevents the logger from causing issues in production
            if (error instanceof DOMException && error.name === 'QuotaExceededError') {
                // Clear old logs and try again
                this.clear();
                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify([entry]));
                } catch {
                    // Still failing, give up
                }
            }
        }
    }

    private getUserId(): number | null {
        try {
            // Try to get user ID from localStorage
            const possibleKeys = ['_ra_session', 'user', 'auth_user'];
            for (const key of possibleKeys) {
                const value = localStorage.getItem(key);
                if (value) {
                    try {
                        const parsed = JSON.parse(value);
                        if (parsed.id || parsed.user?.id) {
                            return parsed.id || parsed.user.id;
                        }
                    } catch {
                        // Continue to next key
                    }
                }
            }
        } catch {
            // Ignore errors
        }
        return null;
    }

    /**
     * Get all stored logs from localStorage
     */
    public getLogs(): LogEntry[] {
        try {
            const stored = localStorage.getItem(STORAGE_KEY);
            if (stored) {
                return JSON.parse(stored);
            }
        } catch {
            // If parsing fails, return empty array
        }
        return [];
    }

    /**
     * Get logs filtered by level
     */
    public getLogsByLevel(level: LogEntry['level']): LogEntry[] {
        return this.getLogs().filter((log) => log.level === level);
    }

    /**
     * Get error logs only
     */
    public getErrorLogs(): LogEntry[] {
        return this.getLogsByLevel('error');
    }

    /**
     * Clear all stored logs
     */
    public clear(): void {
        localStorage.removeItem(STORAGE_KEY);
    }

    /**
     * Clean old logs (keep only the most recent MAX_LOGS)
     */
    private cleanOldLogs(): void {
        const logs = this.getLogs();
        if (logs.length > MAX_LOGS) {
            const trimmedLogs = logs.slice(-MAX_LOGS);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(trimmedLogs));
        }
    }

    /**
     * Export logs as JSON string (useful for debugging)
     */
    public exportLogs(): string {
        const logs = this.getLogs();
        return JSON.stringify(logs, null, 2);
    }

    /**
     * Export logs as downloadable file
     */
    public downloadLogs(): void {
        const logs = this.getLogs();
        const dataStr = JSON.stringify(logs, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr);

        const exportFileDefaultName = `browser-logs-${new Date().toISOString().split('T')[0]}.json`;

        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
    }

    /**
     * Manually log a message
     */
    public log(level: LogEntry['level'], message: string, data?: unknown): void {
        this.storeLog(level, message, data);
    }

    /**
     * Destroy the logger and restore original console methods
     */
    public destroy(): void {
        // Restore original console methods
        console.log = this.originalConsole.log;
        console.info = this.originalConsole.info;
        console.warn = this.originalConsole.warn;
        console.error = this.originalConsole.error;
    }
}

// Create singleton instance
let browserLoggerInstance: BrowserLogger | null = null;

export const getBrowserLogger = (): BrowserLogger => {
    if (!browserLoggerInstance) {
        browserLoggerInstance = new BrowserLogger();
    }
    return browserLoggerInstance;
};

export default BrowserLogger;

/**
 * Utility function to view logs in browser console
 * Call this from DevTools Console: viewLogs()
 */
if (typeof window !== 'undefined') {
    (window as any).viewLogs = () => {
        const logger = getBrowserLogger();
        const logs = logger.getLogs();
        console.table(logs.map((log) => ({
            Time: new Date(log.timestamp).toLocaleTimeString(),
            Level: log.level.toUpperCase(),
            Message: log.message.substring(0, 50) + (log.message.length > 50 ? '...' : ''),
            URL: log.url,
        })));
        console.log('Total logs:', logs.length);
        console.log('Error logs:', logger.getErrorLogs().length);
        return logs;
    };

    (window as any).clearLogs = () => {
        const logger = getBrowserLogger();
        logger.clear();
        console.log('Logs cleared');
    };

    (window as any).downloadLogs = () => {
        const logger = getBrowserLogger();
        logger.downloadLogs();
    };

    (window as any).exportLogs = () => {
        const logger = getBrowserLogger();
        console.log(logger.exportLogs());
    };
}
