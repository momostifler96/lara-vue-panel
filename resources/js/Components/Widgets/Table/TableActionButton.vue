<template>
    <button class="table-action" :class="color" @click="$emit('exec', item)">
        <span v-html="icon" class="w-4 h-4"></span>
        <span>{{ label }}</span>
    </button>
</template>
<script setup lang="ts">
defineProps<{
    item: any;
    color: any;
    icon: string;
    label: string;
    action: any;
}>();
</script>
