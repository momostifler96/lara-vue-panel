<template>
    <div class="relative">
        <button ref="btn" class="editor-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                <path
                    d="M8.465 11.293c1.133-1.133 3.109-1.133 4.242 0l.707.707 1.414-1.414-.707-.707c-.943-.944-2.199-1.465-3.535-1.465s-2.592.521-3.535 1.465L4.929 12a5.008 5.008 0 0 0 0 7.071 4.983 4.983 0 0 0 3.535 1.462A4.982 4.982 0 0 0 12 19.071l.707-.707-1.414-1.414-.707.707a3.007 3.007 0 0 1-4.243 0 3.005 3.005 0 0 1 0-4.243l2.122-2.121z">
                </path>
                <path
                    d="m12 4.929-.707.707 1.414 1.414.707-.707a3.007 3.007 0 0 1 4.243 0 3.005 3.005 0 0 1 0 4.243l-2.122 2.121c-1.133 1.133-3.109 1.133-4.242 0L10.586 12l-1.414 1.414.707.707c.943.944 2.199 1.465 3.535 1.465s2.592-.521 3.535-1.465L19.071 12a5.008 5.008 0 0 0 0-7.071 5.006 5.006 0 0 0-7.071 0z">
                </path>
            </svg>
        </button>
        <div ref="dropdown" class="lvp-editor-dropdown">
            <div class="editor-dropdow-content flex gap-2 items-center">
                <div class="flex">
                    <input type="text" placeholder="url" @keypress.enter="onValidated"
                        class="w-full h-7 px-1 text-sm border-b rounded border-gray-200 focus:outline-none" />
                </div>
                <button class="flex items-center justify-center w-7 h-7 hover:bg-gray-200 rounded" @click="onClear">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
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
    props.editor
        .chain()
        .focus()
        .extendMarkRange('link')
        .setLink({ href: url })
        .run()
    dropdown.value.classList.remove('editor-dropdown-open')
    btn.value.classList.remove('active')
}
const onClear = (e: InputEvent) => {
    props.editor
        .chain()
        .focus()
        .extendMarkRange('link')
        .unsetLink()
        .run()
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