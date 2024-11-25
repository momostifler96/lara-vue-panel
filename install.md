## Install package

```bash
composer require momoledev/laravue-panel --dev
```

## Intall npm packages

```bash
npm install apexcharts
npm install vue3-apexcharts
npm install pinia
npm install pinia-plugin-persistedstate
npm install sass
npm install @headlessui/vue
npm install vue-awesome-paginate
npm install maska
npm install pinia
npm install pinia-plugin-persistedstate
npm install primevue
npm install cropperjs
npm install @primevue/themes
npm install @tailwindcss/forms
npm install @vitejs/plugin-vue
```

## Intall panel module

```bash
php artisan lvp:install
```

## Install codes

## Intall tailwind config

```javascript
// copy and past the following code in your tailwind config file
import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import lvpPreset from "./vendor/momoledev/laravue-panel/tailwind.config.preset";

/** @type {import('tailwindcss').Config} */
export default {
  presets: [lvpPreset],
  content: [
    "./vendor/momoledev/laravue-panel/resources/js/**/*.{js,ts,vue}",
    "./vendor/momoledev/laravue-panel/resources/views/**/*.blade.php",
    "./vendor/momoledev/laravue-panel/src/**/*.php",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.vue",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ["Figtree", ...defaultTheme.fontFamily.sans],
      },
    },
  },
  safelist: [
    {
      pattern: /grid-cols-(\d+)/,
      variants: ["md", "lg", "xl"],
    },
    {
      pattern: /grid-rows-(\d+)/,
      variants: ["md", "lg", "xl"],
    },
    {
      pattern: /gap-(\d+)/,
      variants: ["md", "lg", "xl"],
    },
    {
      pattern: /col-span-(\d+)/,
      variants: ["md", "lg", "xl"],
    },
  ],
};
```

## Intall vite config

```javascript
// copy and past the following code in your vite config file
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
  plugins: [
    laravel({
      input: "resources/js/app.ts",
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  build: {
    rollupOptions: {
      external: ["cropperjs"],
    },
  },
  resolve: {
    alias: {
      lvp: path.resolve(
        __dirname,
        "vendor/momoledev/laravue-panel/resources/js"
      ),
      "@": path.resolve(__dirname, "resources/js"),
    },
  },
});
```

## Intall tsconfig

```javascript
// copy and past the following code in your tsconfig file on compilerOptions.paths
"lvp/*": ["./vendor/momoledev/laravue-panel/resources/js/*"],
"@/*": ["./resources/js/*"],
"ziggy-js": ["./vendor/tightenco/ziggy"]
```

## Stetup app.ts file

```javascript
// Add the following code in your app.ts file after imported Modules
import LVP from "lvp/Plugins/lvp/index";
app.use(LVP);
```
