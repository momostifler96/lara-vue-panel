<template>
    <div class="grid mb-5" :class="`grid-cols-${grid_cols} gap-${gap}`">
        <div v-for="(field, i) in fields" :class="`col-span-${field.props.colspan}`">
            <component :is="form_fields[field.type]" v-bind="field.props" v-model="formData[field.props.name]"
                :errorText="errorIsArray($page.props.errors, field.props.name)" class="my-2"
                @change="updateField(field.props.name, $event, field.eventsListeners.change)" :class="[
                    `col-span-${field.props.colspan}`,
                    {
                        'col-span-full': field.props.colspan == 'full',
                    },
                ]" />
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

</script>