import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path'


export default defineConfig({
    plugins: [
        laravel({
            input: './resources/js/app.ts',
            refresh: true,
        }),
        vue({
            template: {
                base: null,
                includeAbsolute: false,
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            // eslint-disable-next-line no-undef
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
