<template>
  <div>
    <label for="" class="text-sm capitalize">{{ label }}</label>
    <MultiSelect
      v-if="props.multiple"
      v-model="selected_values"
      :options="options"
      :placeholder="placeholder"
      :option-label="optionLabel"
      :option-value="optionValue"
      :filter="filter"
      :multiple="multiple"
      :showToggleAll="false"
      :maxSelectedLabels="1"
      selectedItemsLabel="Selected {0} items"
    />
    <Dropdown
      v-else
      v-model="selected_values"
      :options="options"
      :placeholder="placeholder"
      :option-label="optionLabel"
      :option-value="optionValue"
      :filter="filter"
      :multiple="multiple"
    />
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
  multiple: Boolean,
});

const emit = defineEmits(["update:modelValue"]);
// console.log("props.modelValue dropdown filter", props.modelValue);
const selected_values = ref(
  props.multiple
    ? props.modelValue
      ? props.modelValue.split(",").filter((v) => v !== "")
      : []
    : props.modelValue
);
// console.log("value filter drop-down", value.value);

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
