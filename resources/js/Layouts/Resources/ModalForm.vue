<template>
  <template v-if="$page.props.errors">
    <span>
      {{ updateLoadErrors($page.props.errors) }}
    </span>
  </template>
  <FormModal :show="show" @submit="submit" @close="cancel" :modalTitle="titles[action].title"
    :cancelLabel="titles[action].cancel" :submitLabel="titles[action].submit" size="md">

    <FormEngine v-bind="props.form_widget.props" :form-data="formData" />
  </FormModal>
</template>
<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import FormModal from "lvp/Components/Dialogs/FormModal.vue";
import FormEngine from "lvp/Components/Widgets/FormEngine.vue";
const props = defineProps({
  show: Boolean,
  titles: {
    type: Object,
    required: true,
  },
  form_widget: {
    type: Object,
    required: true,
  },
  action: {
    type: String,
    default: "create",
  },
  routes: {
    type: Object as () => any,
    required: true,
  },
  formData: {
    type: Object as () => any,
    required: true,
  },
  errors: Object,
});

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

  router.post(route(props.routes[props.action == 'create' ? 'store' : 'update']), props.formData, {
    onSuccess: () => {
      formErrors.value = {};
      emit("close", true);
    },
  });
};
const cancel = () => {
  formErrors.value = {};
  emit("close", true);
};


onMounted(() => { });
</script>
