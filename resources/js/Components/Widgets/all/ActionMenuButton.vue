<template>
    <button class="table-action" :class="color" @click="$emit('exec', action)">
        <span v-html="icon" class="w-5 h-5"></span>
        <span>{{ label }}</span>
    </button>
</template>
<script setup lang="ts">
defineProps<{
    color: any;
    icon: string;
    label: string;
    action: any;
}>();
</script>
