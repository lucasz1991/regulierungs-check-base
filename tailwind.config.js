import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import fs from 'fs';
import path from 'path';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        ...getAllCacheFiles('./storage/framework/cache/data/'),
        './resources/views/**/*.blade.php',
        './resources/views/**/**/*.blade.php',
        './resources/views/**/**/**/*.blade.php',
        './app/Http/Livewire/**/*.php',
        './app/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Quicksand', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#084058',
                    light: '#084058',
                    dark: '#084058',
                    50: '#084058',
                    100: '#073a50',
                    200: '#063348',
                    300: '#052d40',
                    400: '#042638',
                    500: '#032030',
                    600: '#021a28',
                    700: '#011420',
                    800: '#000e18',
                    900: '#000810',
                },
                secondary: {
                    DEFAULT: '#0c968e',
                    light: '#0c968e',
                    dark: '#0c968e',
                    50: '#0c968e',
                    100: '#0a7f7d',
                    200: '#096f6c', 
                    300: '#085f5b',
                    400: '#064f4a',
                    500: '#053f39',
                    600: '#042f28',
                    700: '#032f28',
                    800: '#021f18',
                    900: '#011f18',
                },
                transparent: {
                    DEFAULT: '#fff0',
                },
            },
        },
    },

    plugins: [forms, typography],
    safelist: [
        'bg-green-500', 
        'bg-yellow-500', 
        'bg-red-500', 
        'bg-blue-500', 
        'bg-white',
        'fill-green-500',
        'fill-yellow-500', 
        'fill-red-500', 
        'fill-blue-500', 
        'fill-white',
      ],
};
function getAllCacheFiles(dir, fileList = []) {
    try {
        const files = fs.readdirSync(dir);
        files.forEach(file => {
            const filePath = path.join(dir, file);
            if (fs.statSync(filePath).isDirectory()) {
                getAllCacheFiles(filePath, fileList); // Rekursive Suche in Unterordnern
            } else {
                fileList.push(filePath); // Datei zur Liste hinzufügen
            }
        });
    } catch (err) {
        console.error("Fehler beim Lesen der Cache-Dateien:", err);
    }
    return fileList;
}