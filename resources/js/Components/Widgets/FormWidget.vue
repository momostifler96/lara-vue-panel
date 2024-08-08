<template>
  <form
    @submit.prevent="submitForm"
    :class="[{ 'lvp-card': _props.isCard }, 'col-span-3']"
  >
    <div class="lvp-card-header">
      <h3 v-if="_props.title.length > 1" class="text-xl font-bold pb-4">
        {{ _props.title }}
      </h3>
      <slot name="header"></slot>

      <div class=""></div>
    </div>
    <div class="lvp-card-body">
      <div
        class="grid"
        :style="`grid-template-columns: repeat(${_props.cols.all}, minmax(0, 1fr));gap:${_props.gap}px`"
      >
        <template v-for="(field, i) in _props.fields">
          <component
            :is="form_fields[field.component]"
            v-bind="field.props"
            v-model="_formData[field.name]"
            :errorText="errorIsArray($page.props.errors, field.name)"
            @change="
              updateField(field.name, $event, field.eventsListeners.change)
            "
            class=""
            :class="[
              `col-span-${field.colspan}`,
              {
                'col-span-full': field.colspan == 'full',
              },
            ]"
          />
        </template>
      </div>
    </div>
    <div class="lvp-card-footer" :class="{ 'pt-4': !_props.isCard }">
      <SimpleButton class="" type="submit" :class="_props.submitBtnClass">{{
        _props.submitBtnLabel
      }}</SimpleButton>
      <slot name="footer"></slot>
    </div>
  </form>
  <ConfirmationModal
    :show="confirmation_modal.show"
    icon="info"
    :title="confirmation_modal.title"
    :body="confirmation_modal.body"
    @onResponse="confirmation_modal.onConfirm"
  />
</template>
<script setup lang="ts">
import { router, usePage } from "@inertiajs/vue3";
import FormSelectField from "lvp/Components/Forms/FormSelectField.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import TextAreaField from "lvp/Components/Forms/TextAreaField.vue";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import FileUploader from "lvp/Components/Forms/FileUploader.vue";
import { reactive, ref } from "vue";
import DatePicker from "lvp/Components/Forms/DatePicker.vue";
import SwitchToggle from "lvp/Components/Forms/SwitchToggle.vue";
import ConfirmationModal from "../Dialogs/ConfirmationModal.vue";

const _props = defineProps({
  fields: {
    type: Array<any>,
    required: true,
  },
  formData: {
    type: Object,
    required: true,
  },
  isCard: Boolean,
  preventSubmit: {
    type: Boolean,
    default: false,
  },
  isHeadless: Boolean,
  confirmBeforeSubmit: Boolean,
  title: {
    type: String,
    default: "Title",
  },
  lvpAction: {
    type: String,
    default: "",
  },
  action: {
    type: String,
    default: "store",
  },
  onSubmit: {
    type: String,
    default: "store",
  },
  submitBtnLabel: {
    type: String,
    default: "Submit",
  },
  submitBtnClass: {
    type: String,
    default: "btn-primary",
  },
  confirmationTitle: {
    type: String,
    default: "Confirmation",
  },
  confirmationMessage: {
    type: String,
    default: "Are you sure?",
  },
  method: {
    type: String as () => "get" | "post" | "put" | "delete" | "patch",
    default: "post",
  },
  cols: {
    type: Object,
    default: 2,
  },
  gap: {
    type: Number,
    default: 4,
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

const _formData = reactive<{ [k: string]: any }>({
  ..._props.formData,
  lvp_action: _props.lvpAction,
});

let field_debounce = <{ [k: string]: any }>{};

const submitForm = () => {
  if (_props.confirmBeforeSubmit) {
    askConfirmation();
  } else {
    submit();
  }
};
const emit = defineEmits(["onSubmit"]);
const submit = () => {
  if (_props.preventSubmit) {
    emit("onSubmit", _formData);
  } else if (_props.method == "get") {
    router.get(_props.action, _formData);
  } else if (_props.method == "put") {
    router.put(_props.action, _formData);
  } else if (_props.method == "post") {
    router.post(_props.action, _formData);
  } else if (_props.method == "delete") {
    router.delete(_props.action, _formData);
  } else if (_props.method == "patch") {
    router.patch(_props.action, _formData);
  }
};

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

const confirmation_modal = reactive({
  show: false,
  title: _props.confirmationTitle,
  body: _props.confirmationMessage,
  onConfirm: (rsp: boolean) => {
    if (rsp) {
      submit();
    }
    confirmation_modal.show = false;
  },
  onCancel: () => {
    confirmation_modal.show = false;
    confirmation_modal.title = "";
    confirmation_modal.body = "";
  },
});

const askConfirmation = () => {
  confirmation_modal.show = true;
};
</script>
