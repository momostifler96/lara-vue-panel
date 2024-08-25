<template>
    <button class="editor-button" @click="editor.chain().focus().setTextAlign('justify').run()"
        :class="{ 'active': editor.isActive({ textAlign: 'justify' }) }">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            style="fill: rgba(0, 0, 0, 1);">
            <path d="M4 7h16v2H4zm0-4h16v2H4zm0 8h16v2H4zm0 4h16v2H4zm2 4h12v2H6z"></path>
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