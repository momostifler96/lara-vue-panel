<template>
    <div class="flex w-full p-3 my-3 text-sm bg-opacity-25 bg-lvp-danger text-lvp-danger"
        v-if="page.props.flash.alert && page.props.flash.alert.length > 0">
        {{ page.props.flash.alert }}
    </div>
</template>
<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";

// Use the context in your component
const page = usePage() as any; // Assuming usePage() returns the context

</script>
