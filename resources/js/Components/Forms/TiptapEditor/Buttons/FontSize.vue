<template>
    <button ref="btn" type="button" class="editor-button dropdown">
        <span>{{ activeTextType }}</span>
        <span>▾</span>
    </button>
    <div ref="dropdown" class="editor-dropdown">
        <div class="editor-dropdow-content">
            <div v-for="textType in textTypes" class="editor-dropdown-item">
                <button type="button" class="editor-dropdown-item-button"
                    :class="{ 'active': activeTextType === textType.name }" @click="textType.command">
                    {{ textType.name }}
                </button>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { Editor } from '@tiptap/vue-3'
import { computed, onMounted, ref } from 'vue';
import { createDropdown } from '../Helper';

const props = defineProps({
    editor: {
        type: Object as () => any,
        required: true
    }
})

const btn = ref()
const dropdown = ref()
const textTypes = [
    {
        name: '3XL',
        icon: '3xl',
        command: () => props.editor.chain().focus().setFontSize({ fontSize: '3xl' }).run(),
    },
    {
        name: 'Heading 1',
        icon: 'heading',
        command: () => props.editor.chain().focus().toggleHeading({ level: 1 }).run(),
    }, {
        name: 'Heading 2',
        icon: 'heading',
        command: () => props.editor.chain().focus().toggleHeading({ level: 2 }).run(),
    }, {
        name: 'Heading 3',
        icon: 'heading',
        command: () => props.editor.chain().focus().toggleHeading({ level: 3 }).run(),
    }
]

const activeTextType = computed(() => {
    if (props.editor.isActive('paragraph')) {
        return 'Paragraph'
    } else if (props.editor.isActive('heading', { level: 1 })) {
        return 'Heading 1'
    } else if (props.editor.isActive('heading', { level: 2 })) {
        return 'Heading 2'
    } else if (props.editor.isActive('heading', { level: 3 })) {
        return 'Heading 3'
    } else {
        return 'Paragraph'
    }
})

onMounted(() => {
    createDropdown(btn.value, dropdown.value);
})
</script>
<style>
.editor-dropdown {
    width: 150px;
}
</style>