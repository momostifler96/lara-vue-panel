<template>
  <div class="flex flex-col">
    <label for="">
      <span class="text-sm capitalize flex">{{ label }}</span>
    </label>
    <Switch
      :modelValue="getModelValue()"
      @update:modelValue="$emit('update:modelValue', getValue($event))"
      :class="getModelValue() ? 'lvp-bg-primary' : 'bg-gray-300'"
      class="relative inline-flex items-center h-6 rounded-full w-11"
    >
      <span
        :class="getModelValue() ? 'translate-x-6' : 'translate-x-1'"
        class="inline-block w-4 h-4 transition transform bg-white rounded-full"
      />
    </Switch>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Switch } from "@headlessui/vue";
const props = defineProps<{
  modelValue: boolean | string;
  label: string;
  trueValue: string;
  falseValue: string;
}>();

const getValue = ($event: boolean) => {
  return $event ? props.trueValue ?? true : props.falseValue ?? false;
};

const getModelValue = () => {
  return typeof props.modelValue == "boolean"
    ? props.modelValue
    : props.modelValue == props.trueValue;
};
</script>
