import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-slate-800', 'bg-slate-900', 'hover:bg-slate-900', 'hover:bg-black',
        'bg-orange-500', 'bg-orange-600', 'hover:bg-orange-600', 'hover:bg-orange-700',
        'bg-emerald-600', 'hover:bg-emerald-700',
        'bg-red-600', 'bg-red-700', 'hover:bg-red-700', 'hover:bg-red-800',
        'text-red-200', 'text-red-700', 'border-red-600', 'border-red-700', 'border-red-800',
        'bg-red-50', 'bg-red-100', 'hover:bg-red-600',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
