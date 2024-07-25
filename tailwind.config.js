import preset from './tailwind.config.preset'

export default {
    presets: [preset],
    content: [

        './vendor/momoledev/lvp/resources/js/**/*.{js,ts,vue}',
        './vendor/momoledev/lvp/resources/views/**/*.blade.php',
        './vendor/momoledev/lvp/src/**/*.php',
    ],
}
