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
      :formData="_formData"
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
    riquired: true,
  },
  fields: {
    type: Object,
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
  defaultData: {
    type: Object as () => any,
    riquired: true,
  },
  errors: Object,
});
const _formData = ref({});
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
  router.post(route(props.routes[props.action]), _formData.value, {
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
      const form_fields = props.fields.map((field) => field.name);
      _formData.value.id = props.defaultData.id;
      for (let index = 0; index < form_fields.length; index++) {
        _formData.value[form_fields[index]] =
          props.defaultData[form_fields[index]];
      }
    }
  }
);

onMounted(() => {});
</script>
