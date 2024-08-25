<template>
    <div class="relative">
        <button ref="btn" class="editor-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                <path d="M4 5h13v7h2V5c0-1.103-.897-2-2-2H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h8v-2H4V5z"></path>
                <path d="m8 11-3 4h11l-4-6-3 4z"></path>
                <path d="M19 14h-2v3h-3v2h3v3h2v-3h3v-2h-3z"></path>
            </svg>
        </button>
        <div ref="dropdown" class="lvp-editor-dropdown">
            <div class="editor-dropdow-content flex gap-2 items-center">
                <div class="flex">
                    <input type="text" placeholder="url" @keypress.enter="onValidated"
                        class="w-full h-7 px-1 text-sm border-b rounded border-gray-200 focus:outline-none" />
                </div>

            </div>
        </div>
    </div>

</template>
<script setup lang="ts">
import { Editor } from '@tiptap/vue-3'
import { onMounted, ref } from 'vue';
import { createDropdown } from '../Helper';

const props = defineProps({
    editor: {
        type: Editor,
        required: true
    }
})

const btn = ref()
const dropdown = ref()

const onValidated = (e: InputEvent) => {
    const url = (e.target as HTMLInputElement).value
    console.log('url', url);
    props.editor
        .chain()
        .focus()
        .setImage({ src: url })
        .run()
    dropdown.value.classList.remove('editor-dropdown-open')
    btn.value.classList.remove('active')
}
const onClear = (e: InputEvent) => {

    dropdown.value.classList.remove('editor-dropdown-open')
    btn.value.classList.remove('active')

}
onMounted(() => {
    btn.value.addEventListener('click', (e: Event) => {
        e.preventDefault();
        const old_value = props.editor.getAttributes('link').href;
        dropdown.value.querySelector('input[type="text"]').value = old_value ?? '';
    })
    createDropdown(btn.value, dropdown.value);
})
</script>