<template>
  <PanelLayout :page-title="props.page_titles.title">
    <template #actions>
      <SimpleButton @click="askDeleteConfromation" color="danger" v-if="props.action == 'edit'">{{ props.titles.delete
        }}</SimpleButton>
    </template>

    <form class="" @submit.prevent="submit" ref="formRef">
      <FormEngine v-bind="props.form_component.props" :form-data="_formData.formData" />
      <div class="flex justify-between">
        <div class="flex gap-2">
          <SimpleButton type="submit" @click="submitForm('leave')">{{
            props.page_titles.submit
            }}</SimpleButton>
          <SimpleButton v-if="props.action == 'create'" type="submit" @click="submitForm('reload')">
            {{
              props.page_titles.submit_and_create
            }}</SimpleButton>
        </div>
        <div class="">
          <SimpleButton button-type="link" :href="route(props.routes.index)" color="danger"
            class="flex items-center gap-2">
            {{ props.page_titles.cancel }}
          </SimpleButton>
        </div>
      </div>
    </form>
    <ConfirmationModal :show="confirmation_modal.show" icon="delete" :title="confirmation_modal.title"
      :body="confirmation_modal.body" @onResponse="confirmation_modal.onConfirm" />
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
import FormComponent from "./FormComponent.vue";
import FormEngine from "lvp/Components/Widgets/FormEngine.vue";

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

const submitForm = (type: "reload" | "leave") => {
  _formData.after_save = type;
  submit();
};

const _formData = reactive<{ [k: string]: any }>({
  props: props,
  formData: {
    ...props.form_data,
    after_save: "leave",
  },
});

const submit = () => {
  router.post(
    route(props.routes.submit),
    _formData.formData
  );
};
const confirmation_modal = reactive({
  show: false,
  title: '',
  body: '',
  // title: props.titles['edit'].delete_confirmation_title,
  // body: props.titles['edit'].delete_confirmation_body,
  current_id: "",
  onConfirm: (rsp: boolean) => {
    if (rsp) {
      router.delete(
        route(props.routes.delete, {
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

// watch(_formData, (val) => {
//   console.log("_formData", val);
// });
</script>
