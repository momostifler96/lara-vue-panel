<template>
    <div class="grid mb-5" :class="`${_grid_cols[grid_cols]} ${_gaps[gap]}`">
        <div v-for="(field, i) in fields" :class="`col-span-${field.props.colspan}`">
            <component :is="form_fields[field.type]" v-bind="field.props" v-model="formData[field.name]"
                :formData="formData" :errorText="errorIsArray($page.props.errors, field.name)" class="my-2"
                @change="updateField(field.name, $event, field.eventsListeners.change)" />
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
import SectionWidget from "../Forms/SectionWidget.vue";

const _props = defineProps({
    fields: {
        type: Object,
        required: true,
    },
    action: {
        type: String as () => "create" | "edit",
        required: true,
    },
    formData: {
        type: Object,
        required: true,
    },
    defaultData: {
        type: Object,
        required: true,
    },
    grid_cols: {
        type: Number,
        required: 1,
    }, gap: {
        type: Number,
        required: 3,
    },
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

const errorIsArray = (errors: any, field: string): string | null => {
    const error = errors[field];
    return error ? (Array.isArray(error) ? error[0] : error) : null;
};

let field_debounce = <{ [k: string]: any }>{};

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
                _props.formData[listener.fields] = new_val;
            } else if (listener.action == "clear") {
                _props.formData[listener.fields] = null;
            } else if (listener.action == "call") {
                const rs = eval(listener.func.replace("params", new_val));
            }
        }, listener.debounce);
    });
};

const _grid_cols = {
    "1": `grid-cols-1`,
    "2": `grid-cols-2`,
    "3": `grid-cols-3`,
    "4": `grid-cols-4`,
    "5": `grid-cols-5`,
    "6": `grid-cols-6`,
    "7": `grid-cols-7`,
    "8": `grid-cols-8`,
    "9": `grid-cols-9`,
    "10": `grid-cols-10`,
    "11": `grid-cols-11`,
    "12": `grid-cols-12`,
};

const _gaps = {
    "1": `gap-1`,
    "2": `gap-2`,
    "3": `gap-3`,
    "4": `gap-4`,
    "5": `gap-5`,
    "6": `gap-6`,
    "7": `gap-7`,
    "8": `gap-8`,
    "9": `gap-9`,
    "10": `gap-10`,
    "11": `gap-11`,
    "12": `gap-12`,
};


</script>