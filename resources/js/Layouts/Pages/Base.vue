<template>
    <Head>
        <title>{{ props.page_titles.meta_title }}</title>
    </Head>
    <PanelLayout :page-title="props.page_titles.title">
        <template #actions> </template>
    </PanelLayout>
</template>
<script setup lang="ts">
import PanelLayout from "lvp/Layouts/Partials/PanelLayout.vue";
import { Link, router, Head, usePage } from "@inertiajs/vue3";
const props = usePage().props;

// defineProps<{
//     page_titles: any;
//     page_data: any;
//     custom_data: any;
//     widgets: any;
//     route_names: any;
// }>();
</script>
