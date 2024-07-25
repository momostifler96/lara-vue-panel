<template>
    <div class="flex flex-col">
        <div class="flex">
            <label v-if="label" :for="id ?? ''" class="text-sm capitalize">{{
                label
            }}</label>
            <span v-if="required" class="text-red-500">*</span>
        </div>
        <input
            :type="type"
            class="text-sm lvp-text-field"
            @input="updateModelValue"
            :value="modelValue"
            :id="id ?? ''"
            :required="required"
            :disabled="disabled"
            :readonly="readonly"
            :placeholder="placeholder ?? ''"
        />
        <small
            v-if="helperText && helperText.length > 0"
            class="text-gray-400"
            >{{ helperText }}</small
        >
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
    required: Boolean,
    disabled: Boolean,
    readonly: Boolean,
});
const emit = defineEmits(["update:modelValue"]);
const updateModelValue = (event: Event) => {
    emit("update:modelValue", (event.target as HTMLInputElement).value);
};
</script>
