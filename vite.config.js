import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        react(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (!id.includes('node_modules')) return;

                    const modulePath = id.split('node_modules')[1].replaceAll('\\', '/');
                    if (modulePath.startsWith('/react/') || modulePath.startsWith('/react-dom/') || modulePath.startsWith('/scheduler/')) return 'react-vendor';
                    if (modulePath.startsWith('/framer-motion/') || modulePath.startsWith('/motion-')) return 'motion-vendor';
                    if (modulePath.startsWith('/gsap/')) return 'gsap-vendor';
                },
            },
        },
    },
});
