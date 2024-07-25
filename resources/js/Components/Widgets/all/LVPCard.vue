<template>
    <div class="bg-white border border-gray-200 lvp-card">
        <div class="lvp-card-header">
            <div class="">
                <slot name="leading" />
                <Test />
                <h3 class="text-xl font-bold">{{ title }}</h3>
            </div>
            <div class="flex justify-end">
                <slot name="action" />
            </div>
        </div>
        <div class="lvp-card-body">
            <slot />
        </div>
        <div v-if="hasFooter" class="lvp-card-footer">
            <slot name="footer" />
        </div>
    </div>
</template>
<script lang="ts" setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    hasFooter: Boolean,
});
</script>
