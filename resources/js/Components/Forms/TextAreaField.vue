<template>
  <div class="flex flex-col">
    <div class="flex">
      <label v-if="label" :for="id ?? ''">{{ label }}</label>
      <span v-if="required" class="text-red-500 h-2">*</span>
    </div>

    <textarea name="" :id="id ?? ''" rows="10" class="lvp-text-area-field" :placeholder="placeholder ?? ''"
      :disabled="disabled" :readonly="readonly" @input="updateModelValue">{{ modelValue }}</textarea>
    <small v-if="helperText && helperText.length > 0" class="text-gray-300">{{
      helperText
      }}</small>
    <small v-if="errorText && errorText.length > 0" class="text-red-500">{{
      errorText
      }}</small>
  </div>
</template>
<script setup lang="ts">
const props = defineProps({
  modelValue: {
    type: String,
    default: null,
  },
  label: {
    type: String as () => string | null | undefined,
    default: null,
  },
  placeholder: {
    type: String as () => string | null | undefined,
    default: null,
  },
  errorText: {
    type: String as () => string | null | undefined,
    default: null,
  },
  helperText: {
    type: String as () => string | null | undefined,
    default: null,
  },
  id: {
    type: String as () => string | null | undefined,
    default: null,
  },
  required: Boolean,
  disabled: Boolean,
  readonly: Boolean,
});
const emit = defineEmits(["update:modelValue", "change"]);
const updateModelValue = (event: Event) => {
  emit("update:modelValue", (event.target as HTMLInputElement).value);
  emit("change", (event.target as HTMLInputElement).value);
};
</script>
