<template>
    <span>{{ data }}</span>
</template>
<script setup lang="ts">
defineProps<{
    field: string;
    data: string;
}>();
</script>
