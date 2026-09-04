import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                choco: {
                    DEFAULT: '#5C3A1E',
                    light: '#7B4F2C',
                    soft: '#A8815A',
                    dark: '#3E2C1F',
                },
                cream: '#F2E8DC',
                beige: '#E5D7C4',
                gold: '#D4AF37',
            },
        },
    },

    plugins: [forms],
};
