import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import liveReload from 'vite-plugin-live-reload';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        react(),
        // Следим за изменениями PHP-файлов для перезагрузки страницы
        liveReload([
            resolve(__dirname, './**/*.php')
        ])
    ],

    // Настройки сервера разработки
    server: {
        origin: 'http://localhost:3000',
        cors: true,
        port: 3000,
        strictPort: true,
        // Рекомендую добавить это для стабильной работы HMR на Ubuntu
        hmr: {
            host: 'localhost',
        },
    },

    // Настройки финальной сборки
    build: {
        outDir: resolve(__dirname, 'assets'),
        assetsDir: '',
        emptyOutDir: true,
        manifest: true,

        rollupOptions: {
            input: {
                main: resolve(__dirname, 'src/js/main.jsx'),
                'main-style': resolve(__dirname, 'src/scss/main.scss'),
            }
        }
    }
});