<template>
  <div class="grid grid-cols-2 gap-4 mb-10">
    <div
      v-for="(field, i) in fields"
      :class="`col-span-${field.props.colspan}`"
    >
      <component
        :is="form_fields[field.component]"
        v-bind="field.props"
        v-model="formData[field.name]"
        :errorText="errorIsArray($page.props.errors, field.name)"
        @change="updateField(field.name, $event, field.eventsListeners.change)"
        :class="[
          `col-span-${field.props.colspan}`,
          {
            'col-span-full': field.props.colspan == 'full',
          },
        ]"
      />
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
        _props.defaultData[listener.fields] = new_val;
      } else if (listener.action == "clear") {
        _props.defaultData[listener.fields] = null;
      } else if (listener.action == "call") {
        const rs = eval(listener.func.replace("params", new_val));
      }
    }, listener.debounce);
  });
};
</script>
