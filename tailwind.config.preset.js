import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    darkMode: 'class',
    content: [
        './vendor/momoledev/lvp/resources/js/**/*.{js,ts,vue}',
        './vendor/momoledev/lvp/resources/views/**/*.blade.php',
        './vendor/momoledev/lvp/src/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                'lvp-primary': '#EEA319',
                'lvp-red': '#f87171',
                'lvp-danger': '#FF0048',
                'lvp-green': '#34d399',
                'lvp-success': '#02DF83',
                'lvp-blue': '#60a5fa',
                'lvp-yellow': '#facc15',
                'lvp-warn': '#facc15',
                'lvp-info': '#227BFF',
                'lvp-purple': '#a855f7',
                'lvp-pink': '#ec4899',

            },

        },
    },
    plugins: [forms, typography]
};
