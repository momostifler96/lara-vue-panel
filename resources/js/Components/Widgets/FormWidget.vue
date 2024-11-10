<template>
  <form @submit.prevent="submitForm" :class="[{ 'lvp-card': _props.isCard }, 'col-span-3']">
    <div class="lvp-card-header">
      <h3 v-if="_props.title.length > 1" class="text-xl font-bold" :class="[{ 'pb-3': !_props.isCard }]">
        {{ _props.title }}
      </h3>
      <slot name="header"></slot>

      <div class=""></div>
    </div>
    <div class="lvp-card-body">
      <div class="grid mb-5" :class="`${_grid_cols[grid_cols]} ${_gaps[gap]}`">
        <div v-for="(field, i) in fields" :class="`col-span-${field.props.colspan}`">
          <component :is="form_fields[field.type]" v-bind="field.props" v-model="_formData[field.name]"
            :formData="_formData" :errorText="errorIsArray($page.props.errors, field.name)" class="my-2"
            @change="updateField(field.name, $event, field.eventsListeners.change)" />
        </div>
      </div>
    </div>
    <div class="lvp-card-footer" :class="{ 'pt-4': !_props.isCard }">
      <SimpleButton class="" type="submit" :class="_props.submitBtnClass">{{
        _props.submitBtnLabel
        }}</SimpleButton>
      <slot name="footer"></slot>
    </div>
  </form>
  <ConfirmationModal :show="confirmation_modal.show" icon="info" :title="confirmation_modal.title"
    :body="confirmation_modal.body" @onResponse="confirmation_modal.onConfirm" />
</template>
<script setup lang="ts">
import { router, usePage } from "@inertiajs/vue3";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import { inject, reactive, ref } from "vue";
import ConfirmationModal from "../Dialogs/ConfirmationModal.vue";

import FormSelectField from "lvp/Components/Forms/FormSelectField.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import TextAreaField from "lvp/Components/Forms/TextAreaField.vue";
import FileUploader from "lvp/Components/Forms/FileUploader.vue";
import DatePicker from "lvp/Components/Forms/DatePicker.vue";
import SwitchToggle from "lvp/Components/Forms/SwitchToggle.vue";
import TextEditor from "../Forms/TiptapEditor/Editor.vue";
import SectionWidget from "../Forms/SectionWidget.vue";

interface Props {
  fields: any[];
  formData: any;
  isCard: boolean;
  cancelBtnLabel: string;
  submitBtnLabel: string;
  submit_url: string;
  preventSubmit: boolean;
  isHeadless: boolean;
  confirmBeforeSubmit: boolean;
  defaultData: any;
  title: string;
  lvpAction: string;
  action: string;
  route: string;
  onSubmit: string;
  submitBtnClass: string;
  confirmationTitle: string;
  confirmationMessage: string;
  method: "post" | "get" | "put" | "delete" | "patch";
  cols: number;
  gap: number;
  grid_cols: number;
}

const _props = withDefaults(defineProps<Props>(), {
  isCard: false,
  preventSubmit: false,
  isHeadless: false,
  confirmBeforeSubmit: false,
  defaultData: {},
  title: "Title",
  lvpAction: "",
  action: "store",
  onSubmit: "store",
  submitBtnLabel: "Submit",
  route: "",
  submitBtnClass: "btn-primary",
  confirmationTitle: "Confirmation",
  confirmationMessage: "Are you sure?",
  method: "post",
  cols: 2,
  gap: 4,
  grid_cols: 1,
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

const _formData = reactive<{ [k: string]: any }>({
  ..._props.formData,
  lvp_action: _props.action,
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
    router.get(_props.route, _formData);
  } else if (_props.method == "put") {
    router.put(_props.route, _formData);
  } else if (_props.method == "post") {
    router.post(_props.route, _formData);
  } else if (_props.method == "delete") {
    router.delete(_props.route, _formData);
  } else if (_props.method == "patch") {
    router.patch(_props.route, _formData);
  }
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
const _grid_cols = <{ [k: string]: any }>{
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

const _gaps = <{ [k: string]: any }>{
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
