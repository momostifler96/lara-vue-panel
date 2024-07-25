<template>
    <Switch
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue', $event)"
        :class="modelValue ? 'lvp-bg-primary' : 'bg-gray-300'"
        class="relative inline-flex items-center h-6 rounded-full w-11"
    >
        <span class="sr-only">Enable notifications</span>
        <span
            :class="modelValue ? 'translate-x-6' : 'translate-x-1'"
            class="inline-block w-4 h-4 transition transform bg-white rounded-full"
        />
    </Switch>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Switch } from "@headlessui/vue";
const props = defineProps<{
    modelValue: boolean;
}>();
</script>
