<template>
    <button class="editor-button" @click="editor.chain().focus().setHorizontalRule().run()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
            <path d="M6 21h12l-6-6-6 6zM18 3H6l6 6 6-6zM3 11h3v2H3zm5 0h3v2H8zm5 0h3v2h-3zm5 0h3v2h-3z"></path>
        </svg>
    </button>
</template>
<script setup lang="ts">
import { Editor } from '@tiptap/vue-3'

defineProps({
    editor: {
        type: Editor,
        required: true
    }
})

</script>