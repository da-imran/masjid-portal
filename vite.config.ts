import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        react(),
        laravel({
            input: ['resources/js/main.tsx', 'resources/js/admin/main.tsx'],
            publicDirectory: 'public',
            buildDirectory: 'build',
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '@/components': path.resolve(__dirname, './resources/js/components'),
            '@/hooks': path.resolve(__dirname, './resources/js/hooks'),
            '@/layouts': path.resolve(__dirname, './resources/js/layouts'),
            '@/lib': path.resolve(__dirname, './resources/js/lib'),
            '@/pages': path.resolve(__dirname, './resources/js/pages'),
            '@/types': path.resolve(__dirname, './resources/js/types'),
        },
    },
    server: {
        port: 5173,
        strictPort: true,
        cors: true,
        proxy: {
            '/api': {
                target: 'http://localhost:8081',
                changeOrigin: true,
            },
            '/storage': {
                target: 'http://localhost:8081',
                changeOrigin: true,
            },
            '/images': {
                target: 'http://localhost:8081',
                changeOrigin: true,
            },
        },
    },
});
