<template>
  <div class="w-full">
    <div class="flex items-center justify-between text-sm">
      <span>Filters</span>
      <button v-if="options.show_reset" class="text-lvp-danger" type="button">
        {{ options.reset_button_label }}
      </button>
    </div>
    <div class="grid grid-cols-4 gap-3 mt-3">
      <component
        v-for="(filter, i) in options.filters"
        :key="i"
        v-bind="filter.props"
        :is="filters_components[filter.component]"
        v-model="_filters[filter.field]"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { TableFilter } from "lvp/Types";
import FilterCheckBox from "../Filters/FilterCheckBox.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import FilterDropdown from "../Filters/FilterDropdown.vue";
import { watch } from "vue";
import { ref } from "vue";
import DatePicker from "lvp/Components/Forms/DatePicker.vue";
const props = defineProps({
  options: {
    type: Object as () => TableFilter,
    required: true,
  },
  loading: Boolean,
  filterData: Object,
});

const filters_components = <{ [k: string]: any }>{
  checkbox: FilterCheckBox,
  text: TextField,
  select: FilterDropdown,
  date: DatePicker,
};

const _filters = ref({ ...props.filterData });
console.log("options", props.options);
let search_debounce: any = null;
const emit = defineEmits(["filtering"]);
watch(_filters.value, (val) => {
  if (search_debounce) clearTimeout(search_debounce);
  search_debounce = setTimeout(() => {
    emit("filtering", val);
  }, 1000);
});
</script>
