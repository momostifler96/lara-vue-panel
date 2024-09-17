<template>
  <span class="flex justify-between">
    <span class="font-bold" v-if="!props.hide_label">{{ props.label }}</span>
    <span>{{ props.value ?? props.data[props.field] }}</span>
  </span>
</template>
<script setup lang="ts">
const props = defineProps({
  label: {
    type: String,
    required: true,
  }, hide_label: {
    type: Boolean,
    required: true,
  },
  field: {
    type: String,
    required: true,
  },
  value: {
    type: Object,
    required: true,
  },
  data: {
    type: Object,
    required: true,
  },
});
</script>
