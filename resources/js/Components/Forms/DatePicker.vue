<template>
  <div class="flex flex-col">
    <div class="flex">
      <label v-if="label && id" :for="id" class="text-sm capitalize">{{
        label
      }}</label>
      <span v-if="required" class="text-red-500">*</span>
    </div>
    <VueDatePicker :model-value="dateValue" @update:model-value="$emit('update:modelValue', formatDateOutput($event))"
      :range="range" :locale="locale" :placeholder="placeholder || ''" :format="formatDate"
      :cancelText="cancelButtonLabel" :selectText="validateButtonLabel" :min-date="minDate" :max-date="maxDate"
      :enable-time-picker="props.type == 'datetime' || props.type == 'time'" :year-picker="props.type == 'year'"
      :month-picker="props.type == 'month-year'" :time-picker="props.type == 'time'" :week-picker="props.type == 'week'"
      :start-date="startDate" />
    <small v-if="helperText && helperText.length > 0" class="text-gray-400">{{
      helperText
    }}</small>
    <small v-if="errorText && errorText.length > 0" class="text-red-500">{{
      errorText
    }}</small>
  </div>
</template>
<script setup lang="ts">
import VueDatePicker from "@vuepic/vue-datepicker";

import { PropType, ref, watch } from "vue";
const props = defineProps({
  label: {
    type: String as () => string | null | undefined,
    default: null,
  },
  placeholder: {
    type: String as () => string | null | undefined,
    default: null,
  },
  errorText: {
    type: String as () => string | null | undefined,
    default: null,
  },
  helperText: {
    type: String as () => string | null | undefined,
    default: null,
  },
  id: {
    type: String as () => string | null | undefined,
    default: null,
  },
  disabled: Boolean,
  readonly: Boolean,
  required: Boolean,
  minDate: String,
  maxDate: String,
  startDate: String,
  validateButtonLabel: {
    type: String,
    default: "Validate",
  },
  cancelButtonLabel: {
    type: String,
    default: "Cancel",
  },
  locale: {
    type: String,
    default: "en-US",
  },

  modelValue: {
    type: Object as () => string | string[] | Date | Date[] | null | undefined,
    required: true,
  },
  type: {
    type: String as PropType<
      "date" | "month" | "month-year" | "year" | "time" | "datetime" | "week"
    >,
    default: "date",
  },
  range: Boolean,
});
const dateValue = ref<string | string[] | Date | Date[] | null | undefined>(props.modelValue);

const emit = defineEmits(["update:modelValue"]);


const getType = (type: string) => {
  switch (type) {
    case "datetime":
      return props.type == "datetime";
    case "date":
      return props.type == "datetime";
    case "month":
      return props.type == "month";
    case "month-year":
      return props.type == "month-year";
    case "year":
      return props.type == "year";
    case "time":
      return props.type == "time";
    default:
      return props.type == "date";
  }
};

const formats = {
  date: (date: Date | null) => {
    return date ? date.toLocaleDateString("fr-FR") : "";
  },
  month: (date: Date | null) => {
    return date ? date.toLocaleDateString("fr-FR", { month: "long" }) : "";
  },
  "month-year": (date: Date | null) => {
    return date
      ? date.toLocaleDateString("fr-FR", {
        month: "long",
        year: "numeric",
      })
      : "";
  },
  year: (date: Date | null) => {
    return date ? date.getFullYear() : "";
  },
  time: (date: Date | null) => {
    return date ? date.toLocaleTimeString("fr-FR") : "";
  },
  datetime: (date: Date | null) => {
    return date ? date.toLocaleString("fr-FR") : "";
  },
  week: (date: Date | null) => {
    // Example implementation for week formatting
    if (!date) return "";
    const startOfWeek = new Date(date);
    startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay()); // Set to the start of the week (Sunday)
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(endOfWeek.getDate() + 6); // Set to the end of the week (Saturday)
    return `${startOfWeek.toLocaleDateString("fr-FR")} - ${endOfWeek.toLocaleDateString("fr-FR")}`;
  },
};
const formatDate = (date: any): any => {
  return Array.isArray(date)
    ? date.map((d) => formats[props.type](d)).join(" - ")
    : formats[props.type](date);
};

const formatDateOutput = (date: any): any => {
  // Format the date as needed, e.g., 'YYYY-MM-DD'
  return Array.isArray(date)
    ? date.map((d) => {
      return d ? (new Date(d)).toISOString() : null
    })
    : date ? (new Date(date)).toISOString() : null;
};
</script>
