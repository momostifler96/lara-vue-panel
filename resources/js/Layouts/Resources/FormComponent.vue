<template>
  <div class="grid grid-cols-2 gap-4 mb-10">
    <template v-for="(field, i) in props.fields">
      <component
        :is="form_fields[field.component]"
        v-bind="field.props"
        v-model="formData[field.field]"
        :errorText="errorIsArray($page.props.errors, field.field)"
        @change="updateField(field.field, $event, field.eventsListeners.change)"
        :class="[
          `col-span-${field.colspan}`,
          {
            'col-span-full': field.colspan == 'full',
          },
        ]"
      />
    </template>
  </div>
</template>
<script setup lang="ts">
import { router, usePage } from "@inertiajs/vue3";
import FormSelectField from "lvp/Components/Forms/FormSelectField.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import Select from "lvp/Components/Forms/Select.vue";
import TextAreaField from "lvp/Components/Forms/TextAreaField.vue";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import FileUploader from "lvp/Components/Forms/FileUploader.vue";
import { reactive, ref } from "vue";
import type { ResourceFormPageProps } from "../../PropsTypes";
import DatePicker from "lvp/Components/Forms/DatePicker.vue";
import SwitchToggle from "lvp/Components/Forms/SwitchToggle.vue";

const _props = defineProps({
  props: {
    type: Object,
    required: true,
  },
  formData: {
    type: Object,
    required: true,
  },
});
const form_fields = <{ [k: string]: any }>{
  "text-field": TextField,
  "text-area-field": TextAreaField,
  "text-editor-field": TextAreaField,
  "select-field": FormSelectField,
  "date-field": DatePicker,
  "file-field": FileUploader,
  "image-field": FileUploader,
  "toggle-field": SwitchToggle,
  "checkbox-field": TextAreaField,
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
