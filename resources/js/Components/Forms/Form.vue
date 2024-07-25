<template>
    <form @submit.prevent="$emit('submit')" class="lvp-form">
        <div class="grid gap-3" :class="[`grid-cols-${gridCols}`]">
            <slot />
        </div>
        <div class="grid grid-cols-2 gap-2 mt-4">
            <SimpleButton type="button">{{ cancelButtonLabel }}</SimpleButton>
            <SimpleButton>{{ submitButtonLabel }}</SimpleButton>
        </div>
    </form>
</template>
<script setup lang="ts">
import SimpleButton from "../Buttons/SimpleButton.vue";
const props = defineProps({
    gridCols: {
        type: Number,
        default: 2,
    },
    submitButtonLabel: {
        type: String,
        default: "Submit",
    },
    cancelButtonLabel: {
        type: String,
        default: "Canecel",
    },
});
</script>
