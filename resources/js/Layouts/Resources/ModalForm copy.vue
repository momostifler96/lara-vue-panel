<template>
  <template v-if="$page.props.errors">
    <span>
      {{ updateLoadErrors($page.props.errors) }}
    </span>
  </template>
  <FormModal
    :show="show"
    @submit="submit"
    @close="cancel"
    :modalTitle="modalTitle"
    :cancelLabel="cancelLabel"
    :submitLabel="submitLabel"
  >
    <FormComponent :props="{ fields: formFields }" :formData="formData" />
    <!-- <div class="grid grid-cols-2 gap-4 mb-5">
      <template v-for="(field, i) in formFields">
        <TextField
          v-if="!field.hidden_on[_action] && field.type === 'text'"
          :class="[
            `col-span-${field.colspan}`,
            {
              'col-span-full': field.colspan == 'full',
            },
          ]"
          v-model="formData[field.field]"
          :label="field.label"
          :placeholder="field.placeholder"
          :readonly="field.readonly_on[action]"
          :disabled="field.disabled_on[action]"
          :errorText="errorIsArray(field.field)"
          :required="field.rules.includes('required')"
        />
        <DatePicker
          v-else-if="!field.hidden_on[props.action] && field.type === 'date'"
          :class="[
            `col-span-${field.colspan}`,
            {
              'col-span-full': field.colspan == 'full',
            },
          ]"
          v-model="formData[field.field]"
          :label="field.label"
          :placeholder="field.placeholder"
          :readonly="field.readonly_on[props.action]"
          :disabled="field.disabled_on[props.action]"
          :type="field.date_type"
          :minDate="field.min_date"
          :maxDate="field.max_date"
          :range="field.is_range"
          :errorText="errorIsArray($page.props.errors, field.field)"
          :required="field.rules.includes('required')"
        />
        <TextAreaField
          v-else-if="!field.hidden_on[_action] && field.type === 'textarea'"
          :class="[
            `col-span-${field.colspan}`,
            {
              'col-span-full': field.colspan == 'full',
            },
          ]"
          v-model="formData[field.field]"
          :label="field.label"
          :placeholder="field.placeholder"
          :readonly="field.readonly_on[action]"
          :disabled="field.disabled_on[action]"
          :errorText="errorIsArray(field.field)"
          :required="field.rules.includes('required')"
        />
        <FormSelectField
          v-else-if="!field.hidden_on[_action] && field.type === 'select'"
          :class="[
            `col-span-${field.colspan}`,
            {
              'col-span-full': field.colspan == 'full',
            },
          ]"
          v-model="formData[field.field]"
          :label="field.label"
          :placeholder="field.label"
          :required="field.rules.includes('required')"
          :readonly="field.readonly_on[action]"
          :disabled="field.disabled_on[action]"
          :errorText="errorIsArray(field.field)"
          :options="field.options"
        />

        <FileUploader
          v-else-if="!field.hidden_on[_action] && field.type === 'file'"
          :class="[
            `col-span-${field.colspan}`,
            {
              'col-span-full': field.colspan == 'full',
            },
          ]"
          v-model="formData[field.field]"
          :label="field.label"
          :placeholder="field.label"
          :required="field.rules.includes('required')"
          :readonly="field.readonly_on[action]"
          :disabled="field.disabled_on[action]"
          :errorText="errorIsArray(field.field)"
          :options="field.options"
        />
      </template>
    </div> -->
  </FormModal>
</template>
<script setup lang="ts">
import { TransitionRoot, TransitionChild } from "@headlessui/vue";
import { ref, onMounted, watch } from "vue";
import { CloseIcon } from "lvp/helpers/lvp_icons";

import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import FormSelectField from "lvp/Components/Forms/FormSelectField.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import Select from "lvp/Components/Forms/Select.vue";
import TextAreaField from "lvp/Components/Forms/TextAreaField.vue";
import SimpleButton from "lvp/Components/Forms/SimpleButton.vue";
import FileUploader from "lvp/Components/Forms/FileUploader.vue";
import FormModal from "lvp/Components/Dialogs/FormModal.vue";
import DatePicker from "lvp/Components/Forms/DatePicker.vue";
import FormComponent from "./FormComponent.vue";

const props = defineProps({
  show: Boolean,
  titles: {
    type: Object,
    riquired: true,
  },
  formFields: {
    type: Object,
    riquired: true,
  },
  defaultData: {
    type: Object as () => any,
    riquired: true,
  },
  action: {
    type: String,
    default: "create",
  },
  routes: {
    type: Object as () => any,
    riquired: true,
  },
});
const formData = ref(props.formData);
const _action = ref(props.action);
const updateLoadErrors = ($errors: any) => {
  formErrors.value = $errors;
};
const emit = defineEmits(["close", "submit"]);
const formErrors = ref(usePage().props.errors);
const errorIsArray = (field: string): string | null => {
  const error = formErrors.value[field];
  return error ? (Array.isArray(error) ? error[0] : error) : null;
};
const submit = () => {
  router.post(
    route(props.routes[_action.value === "create" ? "store" : "update"]),
    formData.value,
    {
      onSuccess: () => {
        formErrors.value = {};
        emit("close", true);
      },
    }
  );
};
const cancel = () => {
  formErrors.value = {};
  emit("close", true);
};
watch(
  () => props.show,
  (value) => {
    formData.value = props.formData;
    _action.value = props.action;
  }
);

//------------------Item form modal-----------
const form_modal = reactive<{
  show: boolean;
  title: string;
  submit_label: string;
  cancel_label: string;
  action: string;
  form_data: { [k: string]: any };
}>({
  show: false,
  title: "create",
  action: "create",
  cancel_label: "Cancel",
  submit_label: "Submit",
  form_data: {},
});

const closeFormModal = () => {
  form_modal.action = "create";
  form_modal.show = false;
  form_modal.title = props.value.titles.form_titles["create"].title;
  form_modal.submit_label =
    props.value.titles.form_titles["create"].submit_button;
  form_modal.cancel_label =
    props.value.titles.form_titles["create"].cancel_button;
  form_modal.form_data = props.value.form_fields.form_data;
};

const createResource = () => {
  if (props.value.form_fields == "page") {
    router.get(route(props.value.resources_routes.create));
  } else {
    form_modal.action = "create";
    form_modal.title = props.value.titles.form_titles["create"].title;
    form_modal.submit_label =
      props.value.titles.form_titles["create"].submit_button;
    form_modal.cancel_label =
      props.value.titles.form_titles["create"].cancel_button;
    form_modal.form_data = props.value.form_fields.form_data;

    form_modal.show = true;
  }
};

const editResource = (item: any) => {
  if (props.value.form_type == "page") {
    router.get(route(props.value.resources_routes.edit, item.id));
  } else {
    form_modal.action = "edit";
    form_modal.title = props.value.titles.form_titles["edit"].title;
    form_modal.submit_label =
      props.value.titles.form_titles["edit"].submit_button;
    form_modal.cancel_label =
      props.value.titles.form_titles["create"].submit_button;
    form_modal.form_data.id = item.id;
    const form_fields = Object.keys(props.value.form_fields.form_data);
    for (let index = 0; index < form_fields.length; index++) {
      form_modal.form_data[form_fields[index]] = item[form_fields[index]];
    }
    form_modal.show = true;
  }
};

const submitFormTata = () => {
  const route_path = route(
    form_modal.action == "create"
      ? props.value.resources_routes.store
      : props.value.resources_routes.update
  );
  router.post(route_path, form_modal.form_data);
};
onMounted(() => {});
</script>
