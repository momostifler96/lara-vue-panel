<template>
  <div class="flex flex-col">
    <div class="flex">
      <label v-if="label" :for="id ?? ''" class="text-sm uc-first-letter">{{
        label
        }}</label>
      <span v-if="required" class="text-red-500">*</span>
    </div>
    <div class="relative lvp-text-field">
      <slot name="prefix" />
      <input :type="type" class="text-sm" @input="updateModelValue" :value="modelValue" :id="id ?? ''"
        :required="required" :disabled="disabled" :readonly="readonly" v-maska="mask"
        :placeholder="placeholder ?? ''" />
      <slot name="surfix" />
    </div>
    <small v-if="helperText && helperText.length > 0" class="text-gray-400">{{
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
  type: {
    type: String as () => "text" | "password" | "email" | "search",
    default: null,
  },
  mask: String,
  required: Boolean,
  disabled: Boolean,
  readonly: Boolean,
});
const emit = defineEmits(["update:modelValue", "change"]);
const updateModelValue = (event: Event) => {
  emit("update:modelValue", (event.target as HTMLInputElement).value);
  emit("change", (event.target as HTMLInputElement).value);
};


const slugMask = (value: string) => {
  return value
    .toLowerCase()
    .replace(/[^a-z0-9-]/g, '') // Autorise uniquement les caractères alphanumériques, tirets et underscores
    .replace(/--+/g, '-').replace(/---+/g, '-') // Remplace les doubles tirets par un seul
    .replace(/_+/g, '-') // Remplace les underscores par des tirets
    .trim(); // Supprime les espaces en début et fin
};
</script>
