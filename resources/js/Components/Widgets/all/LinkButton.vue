<template>
    <Link class="lvp-button" :href="url" :class="color">
        <slot />
    </Link>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
defineProps({
    url: {
        type: String,
        required: true,
    },
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
