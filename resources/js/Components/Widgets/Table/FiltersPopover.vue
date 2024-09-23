<template>
  <Popover v-slot="{ open }" class="relative">
    <PopoverButton class="lvp-table-filter-button">
      <span v-html="FilterIcon" class="w-6 h-6" />
    </PopoverButton>
    <transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
      enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
      leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
      <PopoverPanel class="absolute right-0 z-10 px-4 mt-3 transform sm:px-0">
        <div class="lvp-popover-overlay w-[250px]">
          <div class="lvp-popover-overlay-content">
            <div class="flex items-center justify-between text-sm">
              <span>Filters</span>
              <button v-if="options.show_reset" class="text-lvp-danger" type="button">
                {{ options.reset_button_label }}
              </button>
            </div>
            <div class="lvp-popover-overlay-content-body flex flex-col py-3 gap-y-3">
              <component v-for="(filter, i) in options.filters" :key="i" v-bind="filter"
                :is="filters_components[filter.component]" v-model="_filters[filter.field]" />
            </div>
            <div class="grid grid-cols-2 gap-2 mt-3">
              <button class="lvp-button small" type="button" @click="submit">
                {{ options.submit_button_label }}
              </button>
              <button class="lvp-button small danger" type="button" @click="reset">
                {{ options.reset_button_label }}
              </button>
            </div>
          </div>
        </div>
      </PopoverPanel>
    </transition>
  </Popover>
</template>

<script setup lang="ts">
import {
  Popover,
  PopoverButton,
  PopoverPanel,
  MenuItem,
} from "@headlessui/vue";
import { Link } from "@inertiajs/vue3";
import { FilterIcon } from "lvp/svg_icons";

import { TableFilter } from "lvp/Types";
import FilterCheckBox from "../Filters/FilterCheckBox.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import FilterDropdown from "../Filters/FilterDropdown.vue";
import { computed, reactive, watch, watchEffect } from "vue";
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
