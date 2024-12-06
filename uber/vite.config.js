import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: '0.0.0.0',  // Permet les connexions à partir de n'importe quelle adresse IP
        port: 1207,        // Utilise le port 1206 (ou celui que vous préférez)
        strictPort: true,  // Assurez-vous que Vite utilise toujours ce port
        cors: {
            origin: '*',  // Permet toutes les origines (vous pouvez aussi spécifier une origine si nécessaire)
        },
    },
    plugins: [
        laravel({
            input: [
                'public/sass/app.scss',
                'public/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});