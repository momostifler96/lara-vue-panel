<template>
    <Dropdown
        :modelValue="modelValue"
        @update:modelValue="emit('update:modelValue', $event)"
        :options="options"
        :placeholder="placeholder"
        :option-label="optionLabel"
        :option-value="optionValue"
        :disabled="disabled"
        :class="class"
    />
    <small v-if="helperText && helperText.length > 0" class="text-gray-300">{{
        helperText
    }}</small>
    <small v-if="errorText && errorText.length > 0" class="text-red-500">{{
        errorText
    }}</small>
</template>
<script setup lang="ts">
import Dropdown from "primevue/dropdown";
const props = defineProps({
    modelValue: String,
    options: Array,
    optionLabel: String,
    optionValue: String,
    placeholder: String,
    class: {
        type: String,
        default: "w-full",
    },
    helperText: String,
    errorText: String,
    filter: Boolean,
    disabled: Boolean,
    readonly: Boolean,
    multiple: Boolean,
});

const emit = defineEmits(["update:modelValue"]);
</script>
