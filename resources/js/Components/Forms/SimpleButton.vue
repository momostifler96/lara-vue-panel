<template>
    <button class="lvp-button" :class="color">
        <slot />
    </button>
</template>
<script setup lang="ts">
defineProps({
    color: {
        type: String as () =>
            | "primary"
            | "secondary"
            | "tertiary"
            | "danger"
            | "warning"
            | "success"
            | "info"
            | "back",
        default: "primary",
    },
});
</script>
