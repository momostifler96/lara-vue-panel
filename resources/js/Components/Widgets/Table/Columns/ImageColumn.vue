<template>
  <span class="flex-center" :class="column.css_class" :style="`width:${column.size}px;`">
    <img :src="data ?? column.default" class="w-full h-auto" alt="" />
  </span>
</template>
<script setup lang="ts">
defineProps<{
  field: string;
  data: string;
  column: any;
}>();
</script>
