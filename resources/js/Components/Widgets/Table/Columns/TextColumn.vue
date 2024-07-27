<template>
  <span>{{ Array.isArray(data) ? data.join(",") : data }}</span>
</template>
<script setup lang="ts">
defineProps<{
  field: string;
  data: string;
  column: any;
}>();
</script>
