# For install laravue-panel

# composer require momoledev/laravue-panel:1.0.0

# lvp:intall

# create directory LVP to resources/Pages

# add this to vite config :

`lvp: path.resolve(__dirname, 'vendor/momoledev/laravue-panel/resources/js'),`

# add this line to tsconfig

`"lvp/*":["./vendor/momoledev/laravue-panel/resources/js/*"],`

# Create file ResourceLayout and past

# add this line i tailwind config

# import lvpPreset from './vendor/momoledev/laravue-panel/tailwind.config.preset';

# dd this in tailwindconfig

` './vendor/momoledev/laravue-panel/resources/js/**/*.{js,ts,vue}',
        './vendor/momoledev/laravue-panel/resources/views/**/*.blade.php',
        './vendor/momoledev/laravue-panel/src/**/*.php',`
