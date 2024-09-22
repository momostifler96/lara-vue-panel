<template>
    <div :class="{ 'lvp-card': filled }">
        <div v-show="props.label && props.label.length > 0" :class="{ 'lvp-card-header': filled, 'mb-3': !filled }">
            <div class="">
                <h3 class="text-xl font-bold">{{ props.label }}</h3>
            </div>
            <div class=""></div>
        </div>
        <div :class="`lvp-card-body grid grid-cols-${props.cols} gap-${props.gap}`">
            <div v-for="(section, i) in sections" :key="`header-${i}`">
                <component v-for="(field, i) in section" :is="form_fields[field.type]" v-bind="field.props"
                    :key="`field-${field.type}-${i}`" class="mb-3" v-model="formData[field.props.name]"
                    :formData="formData" />
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

</script>