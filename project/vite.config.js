import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    logLevel: 'info',
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        react(),
        {
            name: 'custom-terminal-output',
            configureServer(server) {
                // Wartet, bis der Server gestartet ist, und gibt dann Text aus
                server.httpServer?.once('listening', () => {
                    setTimeout(() => {
                        console.log('\n  \x1b[35m➜\x1b[0m  \x1b[1mMailpit Dashboard\x1b[0m: \x1b[36mhttp://localhost:8025\x1b[0m');
                    }, 100); // Kurzer Timeout, damit es nach den Standard-Vite-Links kommt
                });
            }
        }
    ],
});
