<template>
    <Link
        :href="href"
        v-if="buttonType == 'link'"
        class="lvp-button"
        :class="color"
        ><slot
    /></Link>
    <button
        v-else-if="buttonType == 'button'"
        class="lvp-button"
        :class="color"
    >
        <slot />
    </button>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
defineProps({
    buttonType: {
        type: String as () => "button" | "link",
        default: "button",
    },
    href: {
        type: String,
        default: "",
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
