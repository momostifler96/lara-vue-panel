import preset from './tailwind.config.preset'

export default {
    presets: [preset],
    content: [

        './vendor/momoledev/lvp/resources/js/**/*.{js,ts,vue}',
        './vendor/momoledev/lvp/resources/views/**/*.blade.php',
        './vendor/momoledev/lvp/src/**/*.php',
    ],
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
        }
    ],
}
