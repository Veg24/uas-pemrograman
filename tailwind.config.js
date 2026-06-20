import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
                sans: ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
                body: ['"DM Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                lumiere: {
                    bg: '#FAF7F2',
                    primary: '#1A1A1A',
                    accent: '#C8882A',
                    deep: '#4A3728',
                    success: '#4CAF82',
                    error: '#E95252',
                    warning: '#F5A623',
                    info: '#3B82F6',
                }
            }
        },
    },

    plugins: [forms],
};
