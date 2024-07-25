<script setup lang="ts">
// Import Vue FilePond
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";
// import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
// import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import { ref } from "vue";

// Import image preview and file type validation plugins
// import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
// import FilePondPluginImagePreview from "filepond-plugin-image-preview";

// Create component
const FilePond = vueFilePond();

const myFiles = ref([]);

const handleFilePondInit = () => {
    console.log("FilePond has initialized");
};
</script>

<template>
    <div>
        <file-pond
            name="test"
            ref="pond"
            label-idle="Drop files here..."
            v-bind:allow-multiple="true"
            accepted-file-types="image/jpeg, image/png"
            v-bind:files="myFiles"
            v-on:init="handleFilePondInit"
        />
    </div>
</template>
