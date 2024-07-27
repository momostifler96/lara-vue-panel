<template>
  <PanelLayout :page-title="props.titles.form_titles[props.action].title">
    <template #actions>
      <SimpleButton
        @click="askDeleteConfromation"
        color="danger"
        v-if="props.action == 'edit'"
        >{{ props.titles.delete_button }}</SimpleButton
      >
    </template>

    <form class="" @submit.prevent="submit" ref="formRef">
      <div class="grid grid-cols-2 gap-4 mb-10">
        <template v-for="(field, i) in props.fields">
          <component
            :is="form_fields[field.component]"
            v-bind="field.props"
            v-model="_formData[field.field]"
            :errorText="errorIsArray($page.props.errors, field.field)"
            @change="
              updateField(field.field, $event, field.eventsListeners.change)
            "
            :class="[
              `col-span-${field.colspan}`,
              {
                'col-span-full': field.colspan == 'full',
              },
            ]"
          />
        </template>
      </div>
      <div class="flex justify-between">
        <div class="flex gap-2">
          <SimpleButton type="submit" @click="submitForm('leave')">{{
            props.titles.form_titles[props.action].submit_button
          }}</SimpleButton>
          <SimpleButton
            v-if="props.action == 'create'"
            type="submit"
            @click="submitForm('reload')"
          >
            {{
              props.titles.form_titles[props.action].submit_button_and_create
            }}</SimpleButton
          >
        </div>
        <div class="">
          <SimpleButton
            button-type="link"
            :href="route(props.resources_routes.index)"
            color="danger"
            class="flex items-center gap-2"
          >
            {{ props.titles.form_titles[props.action].cancel_button }}
          </SimpleButton>
        </div>
      </div>
    </form>
    <ConfirmationModal
      :show="confirmation_modal.show"
      icon="delete"
      :title="confirmation_modal.title"
      :body="confirmation_modal.body"
      @onResponse="confirmation_modal.onConfirm"
    />
  </PanelLayout>
</template>
<script setup lang="ts">
import PanelLayout from "lvp/Layouts/Partials/PanelLayout.vue";
import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import FormSelectField from "lvp/Components/Forms/FormSelectField.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import Select from "lvp/Components/Forms/Select.vue";
import TextAreaField from "lvp/Components/Forms/TextAreaField.vue";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import FileUploader from "lvp/Components/Forms/FileUploader.vue";
import { reactive, ref, watch } from "vue";
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";
import type {
  ResourceFormField,
  ResourceFormPageProps,
} from "../../PropsTypes";
import DatePicker from "lvp/Components/Forms/DatePicker.vue";

const props = usePage().props as unknown as ResourceFormPageProps;

const form_fields = <{ [k: string]: any }>{
  "text-field": TextField,
  "text-area-field": TextAreaField,
  "text-editor-field": TextAreaField,
  "select-field": FormSelectField,
  "date-field": DatePicker,
  "file-field": FileUploader,
  "image-field": FileUploader,
  "toggle-field": TextAreaField,
  "checkbox-field": TextAreaField,
};

const formRef = ref(null);

const errorIsArray = (errors: any, field: string): string | null => {
  const error = errors[field];
  return error ? (Array.isArray(error) ? error[0] : error) : null;
};
const submitForm = (type: "reload" | "leave") => {
  _formData.after_save = type;
  // formRef.value.submit();
};

const _formData = reactive<{ [k: string]: any }>({
  ...props.form_data,
  after_save: "leave",
});
const submit = () => {
  router.post(
    route(props.resources_routes[props.action == "edit" ? "update" : "store"]),
    _formData
  );
};
const confirmation_modal = reactive({
  show: false,
  title: props.titles.delete_confirmation_title,
  body: props.titles.delete_confirmation_body,
  current_id: "",
  onConfirm: (rsp: boolean) => {
    if (rsp) {
      router.delete(
        route(props.resources_routes.delete, {
          id: props.form_data.id,
        })
      );
    }
    confirmation_modal.show = false;
  },
  onCancel: () => {
    confirmation_modal.show = false;
    confirmation_modal.title = "";
    confirmation_modal.body = "";
    confirmation_modal.current_id = "";
  },
});

const askDeleteConfromation = () => {
  confirmation_modal.show = true;
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
  }[][]
) => {
  if (field_debounce[field]) clearTimeout(field_debounce[field]);
  listeners.forEach((listener) => {
    field_debounce[field] = setTimeout(() => {
      if (listener.action == "fill") {
        _formData[listener.fields] = new_val;
      } else if (listener.action == "clear") {
        _formData[listener.fields] = null;
      } else if (listener.action == "call") {
        const rs = eval(listener.func.replace("params", new_val));
      }
    }, listener.debounce);
  });
};

// watch(_formData, (val) => {
//   console.log("_formData", val);
// });
</script>
