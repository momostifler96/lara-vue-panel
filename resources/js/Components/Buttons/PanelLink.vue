<template>
    <Link :href="href" class="lvp-panel-nav-link">
        <span>
            <component :is="icon" />
        </span>
        <span>{{ label }}</span>
    </Link>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
defineProps<{
    href: string;
    icon: any;
    label: string;
}>();
</script>
