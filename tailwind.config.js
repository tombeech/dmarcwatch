import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                forest: {
                    DEFAULT: '#0f1a2e',
                    50: '#f0f4ff',
                    100: '#dbe4ff',
                    200: '#bac8ff',
                    300: '#91a7ff',
                    400: '#748ffc',
                    500: '#5c7cfa',
                    600: '#4263eb',
                    700: '#3b5bdb',
                    800: '#1e3a5f',
                    900: '#0f1a2e',
                    950: '#080e1a',
                },
                lime: {
                    DEFAULT: '#4ade80',
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                },
                cream: {
                    DEFAULT: '#faf9f6',
                    50: '#fdfcfa',
                    100: '#faf9f6',
                    200: '#f5f3ee',
                },
            },
        },
    },

    plugins: [forms, typography],
};
