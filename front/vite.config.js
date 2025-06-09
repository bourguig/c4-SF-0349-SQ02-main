import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    host: true,
    allowedHosts: ['symfony-vue-nginx', 'symfony-vue', '127.0.0.1', 'localhost'], // 👈 replace with your actual hostname
  },
})
