import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [vue()],
    server: {
        host: true, // allows access from Docker / network
        port: 5173, // choose your preferred port
        strictPort: true,
        watch: {
            usePolling: true, // helps with hot reload inside Docker
        },
    },
})