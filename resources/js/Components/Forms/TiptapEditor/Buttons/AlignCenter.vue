<template>
    <button class="editor-button" @click="editor.chain().focus().setTextAlign('center').run()"
        :class="{ 'active': editor.isActive({ textAlign: 'center' }) }">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
            <path d="M4 19h16v2H4zm3-4h10v2H7zm-3-4h16v2H4zm0-8h16v2H4zm3 4h10v2H7z"></path>
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