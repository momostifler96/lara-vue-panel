<template>
  <SwitchToggle :modelValue="val" @update:modelValue="onSelect" />
</template>
<script setup lang="ts">
import SwitchToggle from "lvp/Components/Forms/SwitchToggle.vue";
import { computed } from "vue";

const props = defineProps<{
  field: string;
  data: boolean;
  column: any;
}>();
const emit = defineEmits(["dataEvent"]);
const onSelect = (value: any) => {
  emit("dataEvent", {
    event: "change_toggle",
    action: props.column.action,
    has_confirmation: props.column.has_confirmation,
    confirmation_title: props.column.confirmation_title,
    confirmation_body: props.column.confirmation_body,
    field: props.field,
    value: value ? props.column.true_value : props.column.false_value,
    old_value: props.data,
  });
};

const val = computed(() => {
  return props.data == props.column.true_value;
});
</script>
