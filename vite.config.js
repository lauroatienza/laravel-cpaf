import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',   // Watch Livewire components
                'app/Filament/**',        // Watch Filament resources
                'resources/views/**',     // Watch Blade templates
                'routes/**',              // Watch route changes
            ],
        }),
    ],
})
