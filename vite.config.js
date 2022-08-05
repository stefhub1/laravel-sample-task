import {createVuePlugin} from 'vite-plugin-vue2';

const {resolve} = require('path');
const Dotenv = require('dotenv');

Dotenv.config();

const ASSET_URL = process.env.ASSET_URL || '';

export default {
  plugins: [
    createVuePlugin(),
  ],
  
  root: 'resources',
  base: `${ASSET_URL}/dist/`,
  
  build: {
    sourcemap: true,
    // Generate source maps
    manifest: true,
    
    // Generate manifest where PHP can find the entry point from
    emptyOutDir: true,
    
    // Vite will clear the entire folder here, thus let's place everything in build folder
    outDir: resolve(__dirname, 'public/dist'),
    target: 'es2018',
    rollupOptions: {
      input: [
        'resources/js/app.js'
      ]
    }
  },
  
  server: {
    strictPort: true,
    port: 3000
  },
  
  resolve: {
    alias: {
      '@': '/js',
    }
  },
  
  optimizeDeps: {
    include: []
  }
}