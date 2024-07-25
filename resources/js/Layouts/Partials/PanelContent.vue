<template>
    <div class="w-full">
        <PanelTopbar />
        <main class="lvp-panel-content">
            <div class="h-7">
                <div v-if="breadcrumbs" class="lvp-panel-content-breadcrumb">
                    <template v-for="(breadcrumb, i) in breadcrumbs">
                        <span
                            v-if="i > 0"
                            class="lvp-panel-content-breadcrumb-separator"
                            >&rsaquo;</span
                        >
                        <span v-if="i == breadcrumbs.length - 1">{{
                            breadcrumb.label
                        }}</span>
                        <Link :href="breadcrumb.link" v-else>{{
                            breadcrumb.label
                        }}</Link>
                    </template>
                </div>
            </div>

            <slot name="header">
                <div class="mb-5 lvp-panel-content-header">
                    <h1 class="lvp-panel-content-header-title">
                        {{ pageTitle }}
                    </h1>
                    <span class="lvp-panel-content-header-actions">
                        <slot name="actions" />
                    </span>
                </div>
            </slot>
            <slot />
        </main>
    </div>
</template>
<script setup lang="ts">
import PanelTopbar from "./PanelTopbar.vue";
import { Link } from "@inertiajs/vue3";

interface Breadcrumb {
    label: string;
    link: string;
}

const props = defineProps<{
    pageTitle: string | undefined | null;
    breadcrumbs: Breadcrumb[] | null | undefined;
}>();
</script>
