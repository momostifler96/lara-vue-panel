<template>
    <span class="w-20 flex-center">
        <img :src="data" class="w-full h-auto" alt="" />
    </span>
</template>
<script setup lang="ts">
defineProps<{
    field: string;
    data: string;
    column: any;
}>();
</script>
