import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
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
    optimizeDeps: {
        include: [
            'leaflet/dist/leaflet.js',
            'leaflet-routing-machine/dist/leaflet-routing-machine.js',
            'leaflet-control-geocoder/dist/Control.Geocoder.js',
            'leaflet',
            'leaflet-routing-machine',
            'leaflet-control-geocoder',
            // Dodaj inne pliki z node_modules, jeśli są wymagane
        ],
    },
});
