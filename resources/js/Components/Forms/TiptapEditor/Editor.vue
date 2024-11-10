<template>
    <div class="flex flex-col">
        <div class="flex">
            <label v-if="label" :for="id ?? ''" class="text-sm capitalize">{{
                label
            }}</label>
            <span v-if="required" class="text-red-500">*</span>
        </div>
        <div class="lvp-text-editor lvp-widget-rouned">
            <div class="toolbar" v-if="editor">
                <component :is="tools_list[tool]" v-for="tool in tools" :key="tool" :editor="editor" />
            </div>
            <EditorContent :editor="editor" />
        </div>
        <small v-if="helperText && helperText.length > 0" class="text-gray-400">{{
            helperText
            }}</small>
        <small v-if="errorText && errorText.length > 0" class="text-red-500">{{
            errorText
            }}</small>
    </div>

</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import Highlight from '@tiptap/extension-highlight'
import TextAlign from '@tiptap/extension-text-align'
import { Image } from './Plugins/image'
import Link from '@tiptap/extension-link'
import Underline from '@tiptap/extension-underline'
import Dropcursor from '@tiptap/extension-dropcursor'

import UndoBtn from './Buttons/Undo.vue'
import RedoBtn from './Buttons/Redo.vue'
import TextTypeBtn from './Buttons/TextType.vue'
import BoldBtn from './Buttons/Bold.vue'
import ItalicBtn from './Buttons/Italic.vue'
import FontSizeBtn from './Buttons/FontSize.vue'
import HorizontalRuleBtn from './Buttons/HorizontalRule.vue'
import CodeBtn from './Buttons/Code.vue'
import TextAlignGroup from './Buttons/TextAlign.vue'
import StrikeBtn from './Buttons/Strike.vue'
import UnderlineBtn from './Buttons/Underline.vue'
import HightLineBtn from './Buttons/HightLine.vue'
import LinkBtn from './Buttons/Link.vue'
import ImageBtn from './Buttons/Image.vue'
const props = defineProps({
    modelValue: {
        type: String,
        default: null,
    },
    label: {
        type: String as () => string | null | undefined,
        default: null,
    },
    placeholder: {
        type: String as () => string | null | undefined,
        default: null,
    },
    errorText: {
        type: String as () => string | null | undefined,
        default: null,
    },
    helperText: {
        type: String as () => string | null | undefined,
        default: null,
    }, tools: {
        type: Array as () => any[],
        default: [],
    },
    id: {
        type: String,
        default: null,
    },

    required: Boolean,
    disabled: Boolean,
    readonly: Boolean,
})

const emit = defineEmits(['update:modelValue'])

const editor = ref()

watch(() => props.modelValue, (newValue) => {
    if (newValue !== editor.value.getHTML()) {
        editor.value.commands.setContent(newValue, false)
    }
})

const tools_list = <{ [k: string]: any }>{
    undo: UndoBtn,
    redo: RedoBtn,
    'text-types': TextTypeBtn,
    bold: BoldBtn,
    'divider': HorizontalRuleBtn,
    italic: ItalicBtn,
    code: CodeBtn,
    'text-align': TextAlignGroup,
    strike: StrikeBtn,
    underline: UnderlineBtn,
    'hightline': HightLineBtn,
    link: LinkBtn,
    image: ImageBtn,
}

onMounted(() => {
    editor.value = new Editor({
        content: props.modelValue,
        extensions: [StarterKit, TextAlign.configure({
            types: ['heading', 'paragraph'],
            alignments: ['left', 'right', 'center', 'justify'],
        }), Underline, Image.configure({
            allowBase64: true,
        }), Link.configure({
            defaultProtocol: 'https',
        }), Highlight, Dropcursor],
        onUpdate: (ed) => {
            emit('update:modelValue', editor.value.getHTML())
        },
    })
    console.log('Text editor', editor.value);
})
onBeforeUnmount(() => {
    editor.value.destroy()
})
</script>
<style lang="scss">
.lvp-editor-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 100;
    margin-top: 5px;
    margin-bottom: 5px;
    padding: 5px;
    border-radius: 5px;
    background: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.084);
    display: none;
    width: 150px;

    &.editor-dropdown-open {
        display: block;
    }

    .editor-dropdown-item {}

    .editor-dropdow-content {
        max-height: 200px;
        overflow-y: auto;
    }

    .editor-dropdown-item-button {
        @apply flex items-center text-sm justify-between w-full h-full rounded-md bg-white p-2 py-1 hover:bg-gray-100;
        cursor: pointer;

        &.active {
            @apply bg-gray-200;
        }
    }
}

.lvp-text-editor {
    @apply bg-white divide-y border p-2;
    border-radius: var(--lvp-widget-border-radius);

    .toolbar {
        @apply flex gap-2 items-center flex-wrap;

        .editor-button {
            @apply hover:bg-gray-200 text-gray-700 font-bold py-1 px-1 h-6 flex items-center justify-center text-xs rounded focus:right-0 focus:outline-0;

            svg {
                width: 18px;
                height: 18px;
            }

            &.active {
                @apply bg-gray-200;
            }

            &.dropdown {
                @apply flex items-center justify-between gap-3 px-2;
            }
        }
    }









    .tiptap {
        @apply py-2 outline-0 bg-white;

        h1 {
            @apply text-2xl;
        }

        h2 {
            @apply text-xl;
        }

        h3 {
            @apply text-lg;
        }

        /* List styles */
        ul,
        ol {
            padding: 0 1rem;
            margin: 1.25rem 1rem 1.25rem 0.4rem;

            li p {
                margin-top: 0.25em;
                margin-bottom: 0.25em;
            }
        }

        ol {
            list-style-type: decimal;
        }

        ul {
            list-style-type: disc;
        }

        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 2rem 0;
        }

        a {
            color: blue;
            cursor: pointer;
        }

        pre {
            background-color: #2A2A2A;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            color: #fff;
            font-size: 0.875rem;
            padding: .5rem;
            overflow-x: auto;
            line-height: 1.45;
        }
    }

}
</style>