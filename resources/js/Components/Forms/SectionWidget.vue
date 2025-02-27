<template>
    <div :class="{ 'lvp-card': filled }"
        :style="props.sticky.length > 0 ? `position: sticky; top: ${props.sticky}` : ''">
        <div v-show="props.label && props.label.length > 0" :class="{ 'lvp-card-header': filled, 'mb-3': !filled }">
            <div class="">
                <h3 class="text-lg font-bold">{{ props.label }}</h3>
            </div>
            <div class=""></div>
        </div>
        <div :class="`lvp-card-body grid grid-cols-${props.cols} gap-${props.gap}`">
            <div v-for="(section, i) in sections" :key="`header-${i}`">
                <component v-for="(field, i) in section" :is="form_fields[field.type]" v-bind="field.props"
                    v-model="formData[field.name]" :formData="formData"
                    :errorText="errorIsArray($page.props.errors, field.name)" class="my-2" />

            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import FormSelectField from "lvp/Components/Forms/FormSelectField.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import TextAreaField from "lvp/Components/Forms/TextAreaField.vue";
import FileUploader from "lvp/Components/Forms/FileUploader.vue";
import DatePicker from "lvp/Components/Forms/DatePicker.vue";
import SwitchToggle from "lvp/Components/Forms/SwitchToggle.vue";
import TextEditor from "../Forms/TiptapEditor/Editor.vue";
import { inject } from "vue";
import SectionWidget from "./SectionWidget.vue";

const props = defineProps({
    sections: {
        type: Object,
        default: null,
    },
    formData: {
        type: Object,
        default: null,
    },
    label: {
        type: String as () => string | null | undefined,
        default: null,
    }, header: {
        type: Object,
        required: false,
    }, footer: {
        type: Object,
        required: false,
    }, cols: {
        type: Number,
        default: 1,
    },
    gap: {
        type: Number,
        default: 1,
    }, sticky: {
        type: String,
        default: '',
    }, filled: {
        type: Boolean,
        default: true,
    }
});

const plugins_fields = <{ [k: string]: any }>inject('lvp_form_fields');
const form_fields = <{ [k: string]: any }>{
    "text": TextField,
    "text-area": TextAreaField,
    "text-editor": TextEditor,
    "select": FormSelectField,
    "date": DatePicker,
    "file": FileUploader,
    "image": FileUploader,
    "toggle": SwitchToggle,
    "checkbox": TextAreaField,
    "section": SectionWidget,
    ...plugins_fields
};

// const csl = (val: any) => {
//     console.log('val dd', val, props.formData);
// }
const field_debounce = <{ [k: string]: any }>{};
const updateField = (
    field: string,
    new_val: any,
    listeners: {
        fields: string;
        action: string;
        func: string;
        debounce: number;
    }[]
) => {
    if (field_debounce[field]) clearTimeout(field_debounce[field]);
    listeners.forEach((listener) => {
        field_debounce[field] = setTimeout(() => {
            if (listener.action == "fill") {
                props.formData[listener.fields] = new_val;
            } else if (listener.action == "clear") {
                props.formData[listener.fields] = null;
            } else if (listener.action == "call") {
                const rs = eval(listener.func.replace("params", new_val));
            }
        }, listener.debounce);
    });
};
const errorIsArray = (errors: any, field: string): string | null => {
    const error = errors[field];
    return error ? (Array.isArray(error) ? error[0] : error) : null;
};

</script>