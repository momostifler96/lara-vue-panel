<template>
    <button class="editor-button" @click="editor.chain().focus().toggleItalic().run()"
        :class="{ 'active': editor.isActive('italic') }">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
            <path d="M19 7V4H9v3h2.868L9.012 17H5v3h10v-3h-2.868l2.856-10z"></path>
        </svg>
    </button>
</template>
<script setup lang="ts">
import { Editor } from '@tiptap/vue-3'

defineProps({
    editor: {
        type: Object as () => any,
        required: true
    }
})

</script>