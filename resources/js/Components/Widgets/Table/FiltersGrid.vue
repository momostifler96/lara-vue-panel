<template>
  <div class="lvp-card bg-white">
    <div class="lvp-card-header">
      <span>Filters</span>
      <button v-if="options.show_reset" class="text-lvp-danger" type="button">
        {{ options.reset_button_label }}
      </button>
    </div>
    <div class="lvp-card-body grid grid-cols-4 gap-3">
      <component v-for="(filter, i) in options.filters" :key="i" v-bind="filter"
        :is="filters_components[filter.component]" :class="`col-span-${filter.col_span}`"
        v-model="_filters[filter.field]" />

    </div>
    <div class="lvp-card-footer justify-end" v-if="!options.auto_submit">
      <button class="lvp-button " type="button" @click="submit">
        {{ options.submit_button_label }}
      </button>
      <button class="lvp-button  danger" type="button" @click="reset">
        {{ options.reset_button_label }}
      </button>
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
let search_debounce: any = null;
const emit = defineEmits(["filtering", "reset"]);
watch(_filters.value, (val) => {
  if (!props.options.auto_submit) return;
  if (search_debounce) clearTimeout(search_debounce);
  search_debounce = setTimeout(() => {
    emit("filtering", val);
  }, 1000);
});

const submit = () => {
  emit("filtering", _filters.value);
};
const reset = () => {
  emit("reset");
};
</script>
