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

interface Props {
  show: boolean;
  title: string;
  submit_button_label: string;
  cancel_button_label: string;
  fields: {
    type: string;
    props: any;
    name: string;
    eventsListeners: {
      change: {
        fields: string;
        action: string;
        func: string;
        debounce: number;
      }[];
    };
  }[];
  grid_cols: number;
  gap: number | string;
  defaultData?: any;
  errors?: any;
}

const props = withDefaults(defineProps<Props>(), {
  grid_cols: 1,
  gap: 3,
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
