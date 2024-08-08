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
    :modalTitle="titles[action].title"
    :cancelLabel="titles[action].cancel"
    :submitLabel="titles[action].submit"
  >
    <FormComponent
      v-bind="{ fields, action }"
      :formData="formData"
      :defaultData="defaultData"
    />
  </FormModal>
</template>
<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import FormModal from "lvp/Components/Dialogs/FormModal.vue";
import FormComponent from "./FormComponent.vue";
const props = defineProps({
  show: Boolean,
  titles: {
    type: Object,
    required: true,
  },
  fields: {
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
  router.post(route(props.routes[props.action]), formData.value, {
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
watch(
  () => props.show,
  () => {
    if (props.action == "edit") {
      formData.value = props.defaultData;
    } else {
      formData.value = {};
    }
  }
);

onMounted(() => {});
</script>
