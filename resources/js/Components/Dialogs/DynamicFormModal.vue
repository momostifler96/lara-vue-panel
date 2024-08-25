<template>
  <FormModal :show="show" @submit="submit" @close="cancel" :modalTitle="title" :cancelLabel="cancel_button_label"
    :submitLabel="submit_button_label">
    <FormEngine :fields="fields" :grid_cols="grid_cols" :gap="gap" :formData="formData" :defaultData="defaultData" />
  </FormModal>
</template>
<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import FormModal from "lvp/Components/Dialogs/FormModal.vue";
import FormEngine from "lvp/Components/Widgets/FormEngine.vue";
const props = defineProps({
  show: Boolean,
  title: {
    type: String,
    required: true,
  },
  submit_button_label: {
    type: String,
    required: true,
  }, cancel_button_label: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
  fields: {
    type: Object,
    required: true,
  }, grid_cols: {
    type: Number,
    default: 1,
  }, gap: {
    type: Number,
    default: 3,
  },
  has_password: {
    type: Boolean,
    default: false,
  },

  defaultData: {
    type: Object as () => any,
    required: true,
  },
  errors: Object,
});
const formData = ref({});
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
  emit("submit", { formData: formData.value, password: null });
};
const cancel = () => {
  formErrors.value = {};
  emit("close", true);
};


onMounted(() => { });
</script>
