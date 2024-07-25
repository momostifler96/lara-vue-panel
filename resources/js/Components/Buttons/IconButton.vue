<template>
    <Link :href="href" v-if="buttonType == 'link'" class="lvp-icon-button"
        ><slot
    /></Link>
    <button
        :type="type"
        v-else-if="buttonType == 'button'"
        class="lvp-icon-button"
    >
        <slot />
    </button>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
defineProps({
    buttonType: {
        type: String as () => "button" | "link",
        default: "link",
    },
    href: {
        type: String,
        default: "",
    },
    type: {
        type: String as () => "button" | "submit",
        default: "button",
    },
});
</script>
