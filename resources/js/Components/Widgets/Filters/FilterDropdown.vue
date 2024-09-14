<template>
  <div :class="`col-span-${col_span}`" class="flex flex-col">
    <label for="" class="text-sm capitalize">{{ label }}</label>
    <MultiSelect v-if="props.multiple" v-model="selected_values" :options="options" :placeholder="placeholder"
      :option-label="optionLabel" :option-value="optionValue" :filter="filter" :multiple="multiple"
      :showToggleAll="false" :maxSelectedLabels="1" selectedItemsLabel="{0} éléments sélectionnés" />
    <Dropdown v-else v-model="selected_values" :options="options" :placeholder="placeholder" :option-label="optionLabel"
      :option-value="optionValue" :filter="filter" :multiple="multiple" />
  </div>
</template>
<script setup lang="ts">
import Dropdown from "primevue/dropdown";
import MultiSelect from "primevue/multiselect";
import { computed, ref, watch } from "vue";
const props = defineProps({
  modelValue: {
    type: String,
    required: true,
  },
  options: Array,
  label: String,
  optionLabel: String,
  optionValue: String,
  placeholder: String,
  class: {
    type: String,
    default: "w-full",
  },
  filter: Boolean,
  col_span: String,
  multiple: Boolean,
});

const emit = defineEmits(["update:modelValue"]);
const selected_values = ref(
  props.multiple
    ? props.modelValue
      ? props.modelValue.split(",").filter((v) => v !== "")
      : []
    : props.modelValue
);

watch(
  () => selected_values.value,
  (val) => {
    if (Array.isArray(selected_values.value) && props.multiple) {
      emit("update:modelValue", selected_values.value.join(","));
    } else {
      emit("update:modelValue", selected_values.value);
    }
  }
);
</script>
