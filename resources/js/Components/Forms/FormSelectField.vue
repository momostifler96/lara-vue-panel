<template>
  <div class="">
    <div class="flex">
      <label for="" class="text-sm capitalize">{{ label }}</label>
      <span v-if="required" class="text-red-500 h-2">*</span>
    </div>
    <MultiSelect v-if="multiple" :modelValue="modelValue" @update:modelValue="emit('update:modelValue', $event)"
      :options="options" :placeholder="placeholder" option-label="label" option-value="value" :filter="filter"
      :loading="loading" :max-selected-labels="2" class="w-full" @filter="search"
      selectedItemsLabel="{0} éléments sélectionnés" />
    <Dropdown v-else :modelValue="modelValue" @update:modelValue="emit('update:modelValue', $event)" :options="options"
      :placeholder="placeholder" option-label="label" option-value="value" :filter="filter" :loading="loading"
      class="w-full" @filter="search" />

    <small v-if="helperText && helperText.length > 0" class="text-gray-400">{{
      helperText
    }}</small>
    <small v-if="errorText && errorText.length > 0" class="text-red-500">{{
      errorText
    }}</small>
  </div>
</template>
<script setup lang="ts">
import Dropdown from "primevue/dropdown";
import HTTP from "lvp/helpers/http";
import { ref } from "vue";
import MultiSelect from "primevue/multiselect";
const props = defineProps({
  modelValue: {
    type: [Number, String],
    default: null,
  },
  options: {
    type: Array,
    default: [],
  },
  label: {
    type: String,
    default: null,
  },
  placeholder: {
    type: String,
    default: "Select an option",
  },
  required: {
    type: Boolean,
    default: false,
  },
  filter: {
    type: Boolean,
    default: false,
  },
  multiple: Boolean,
  errorText: {
    type: String as () => string | null | undefined,
    default: null,
  },
  helperText: {
    type: String as () => string | null | undefined,
    default: null,
  },
  ajaxCall: {
    type: Object as () => string | null,
    default: null,
  },
});

const emit = defineEmits(["update:modelValue", "change"]);
console.log("filter", props);
const updateModelValue = (event: Event) => {
  emit("update:modelValue", (event.target as HTMLInputElement).value);
  emit("change", (event.target as HTMLInputElement).value);
};

const options = ref(props.options);
const loading = ref(false);
var search_debounce: any = null;
const search = (val: any) => {
  return;
  loading.value = false;
  if (search_debounce) clearTimeout(search_debounce);
  search_debounce = setTimeout(async () => {
    //@ts-ignore
    loading.value = true;
    const { data } = await HTTP.get(props.ajaxCall + "?search=" + val.value);
    options.value = data.data;
    loading.value = false;
  }, 1000);
};
</script>
