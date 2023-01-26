import { fileURLToPath, URL } from 'url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    laravel([
      'resources/js/vue/app/main.ts',
    ]),
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./resources/js/vue/app', import.meta.url))
    }
  },
})
