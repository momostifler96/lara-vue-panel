import forms from '@tailwindcss/forms'

export default {
    darkMode: 'class',
    content: [
        './vendor/momoledev/laravue-panel/resources/js/**/*.{js,ts,vue}',
        './vendor/momoledev/laravue-panel/resources/views/**/*.blade.php',
        // './vendor/momoledev/laravue-panel/src/**/*.php',
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
                'lvp-disabled': '#B4B4B4FF',

            },

        },
    },
    safelist: [
        {
            pattern: /grid-cols-(\d+)/,
            variants: ['md', 'lg', 'xl'],
        }, {
            pattern: /grid-rows-(\d+)/,
            variants: ['md', 'lg', 'xl'],
        }, {
            pattern: /gap-(\d+)/,
            variants: ['md', 'lg', 'xl'],
        }, {
            pattern: /col-span-(\d+)/,
            variants: ['md', 'lg', 'xl'],
        },
        'text-lvp-primary',
        'text-lvp-warn',
        'text-lvp-disabled',
        'text-lvp-success',
        // 'text-(lvp-primary|lvp-red|lvp-danger|lvp-green|lvp-success|lvp-blue|lvp-yellow|lvp-warn|lvp-info|lvp-purple|lvp-pink|lvp-disabled)',
        // 'bg-(lvp-primary|lvp-red|lvp-danger|lvp-green|lvp-success|lvp-blue|lvp-yellow|lvp-warn|lvp-info|lvp-purple|lvp-pink|lvp-disabled)',
    ],
    plugins: [forms]
};
