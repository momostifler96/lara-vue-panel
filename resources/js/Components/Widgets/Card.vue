<template>
    <div class="lvp-card" :class="{ 'bg-white': filled }">
        <div v-if="hasHeader" class="lvp-card-header">
            <h4 class="my-2 font-bold" v-if="label">{{ label }}</h4>
            <div class="">
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
<script setup lang="ts">
defineProps({
    label: String,
    hasFooter: Boolean,
    filled: Boolean,
    hasHeader: {
        type: Boolean,
        default: false,
    },
});
</script>
