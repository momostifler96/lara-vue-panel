<template>
  <div class="">
    <h5 class="text-sm capitalize">{{ label }}</h5>
    <label
      v-for="(val, i) in options"
      class="flex gap-2 mb-1 text-sm select-none"
      :for="`filter-${i}-${val.value}`"
    >
      <input
        :id="`filter-${i}-${val.value}`"
        type="checkbox"
        v-model="selected_values"
        :value="val.value"
        class="lvp-checkbox"
      />
      <span>{{ val.label }}</span>
    </label>
  </div>
</template>
<script setup lang="ts">
import { ref, watch } from "vue";

const props = defineProps({
  field: {
    type: Object as () => any,
    required: true,
  },
  label: {
    type: Object as () => any,
    required: true,
  },
  options: {
    type: Array<any>,
    required: true,
  },
  modelValue: {
    type: String,
    required: true,
  },
});

const emit = defineEmits(["update:modelValue"]);

const selected_values = ref(
  (props.modelValue ?? "").split(",").filter((v) => v !== "")
);

watch(
  () => selected_values.value,
  (val) => {
    emit("update:modelValue", selected_values.value.join(","));
  }
);
</script>
