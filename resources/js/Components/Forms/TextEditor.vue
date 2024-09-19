<template>
  <div class="lvp-text-editor">
    <div class="flex">
      <label v-if="label" for="">{{ label }}</label>
      <span v-if="required" class="text-red-500 h-2">*</span>
    </div>
    <textarea @input="updateModelValue" name="" id="" cols="30" rows="10">{{
      modelValue
    }}</textarea>
    <small v-if="helperText && helperText.length > 0" class="text-gray-300">{{
      helperText
      }}</small>
    <small v-if="errorText && errorText.length > 0" class="text-red-500">{{
      errorText
      }}</small>
  </div>
</template>
<script setup lang="ts">
const props = defineProps<{
  label: string | null;
  modelValue: string;
  disabled: boolean;
  readonly: boolean;
  placeholder: string;
  required: boolean;
  isError: boolean;
  helperText: string;
  errorText: string;
}>();
const emit = defineEmits(["update:modelValue", "change"]);
const updateModelValue = (event: Event) => {
  emit("update:modelValue", (event.target as HTMLInputElement).value);
  emit("change", (event.target as HTMLInputElement).value);
};
</script>
