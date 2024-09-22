<template>
  <a :href="column.url">
    <TableColumnEngine :column="column.column" :item="data" />
  </a>
</template>
<script setup lang="ts">
import TableColumnEngine from '../../TableColumnEngine.vue';

defineProps<{
  field: string;
  data: string;
  column: any;
}>();
</script>
