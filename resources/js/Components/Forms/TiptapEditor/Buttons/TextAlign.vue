<template>
    <AlignLeft :editor="editor" />
    <AlignCenter :editor="editor" />
    <AlignRight :editor="editor" />
    <AlignJustify :editor="editor" />
</template>
<script setup lang="ts">
import { Editor } from '@tiptap/vue-3'
import AlignLeft from './AlignLeft.vue';
import AlignCenter from './AlignCenter.vue';
import AlignJustify from './AlignJustify.vue';
import AlignRight from './AlignRight.vue';

defineProps({
    editor: {
        type: Object as () => any,
        required: true
    }
})

</script>