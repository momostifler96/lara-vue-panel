<template>
    <Popover v-slot="{ open }" class="relative">
        <PopoverButton class="lvp-table-filter-button">
            <FilterIcon class="w-6 h-6" />
        </PopoverButton>
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-1 opacity-0"
        >
            <PopoverPanel
                class="absolute right-0 z-10 px-4 mt-3 transform sm:px-0"
            >
                <div class="lvp-popover-overlay w-[250px]">
                    <div class="lvp-popover-overlay-content">
                        <div class="flex items-center justify-between text-sm">
                            <span>Filters</span>
                            <button
                                v-if="options.show_reset"
                                class="text-lvp-danger"
                                type="button"
                            >
                                {{ options.reset_button_label }}
                            </button>
                        </div>
                        <template v-if="options.filters.texts.length > 0">
                            <TextField
                                class="mt-3"
                                v-for="(txt, i) in options.filters.texts"
                                :key="i"
                                :label="txt.label"
                                :placeholder="txt.placeholder"
                                v-model="_filters[txt.field]"
                            />
                        </template>
                        <template v-if="options.filters.checkboxs.length > 0">
                            <FilterCheckBox
                                class="mt-3"
                                v-for="(chk, i) in options.filters.checkboxs"
                                :key="i"
                                v-bind="chk"
                                v-model="_filters[chk.field]"
                            />
                        </template>
                        <template v-if="options.filters.dropdowns.length > 0">
                            <FilterDropdown
                                class="mt-3"
                                v-for="(drop, i) in options.filters.dropdowns"
                                :key="i"
                                v-bind="drop"
                                v-model="_filters[drop.field]"
                            />
                        </template>
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
import { FunnelIcon as FilterIcon } from "@heroicons/vue/24/outline";
import { TableFilter } from "lvp/Types";
import FilterCheckBox from "../Filters/FilterCheckBox.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import FilterDropdown from "../Filters/FilterDropdown.vue";
import { computed, reactive, watch, watchEffect } from "vue";
import { ref } from "vue";
const props = defineProps({
    options: {
        type: Object as () => TableFilter,
        required: true,
    },
    loading: Boolean,
    filterData: Object,
});

const filter_data = computed(() => {
    console.log("filter_data", props.filterData);
    const checkboxs = {};
    const checkboxs_keys = Object.keys(props.options.filters.checkboxs);
    // for (let index = 0; index < checkboxs_keys.length; index++) {
    //     if (props.filterData[checkboxs_keys[index]]) {
    //         checkboxs[checkboxs_keys[index]] = props.filterData[
    //             checkboxs_keys[index]
    //         ]
    //             .split(",")
    //             .filter((it) => it !== "");
    //     }
    // }

    return props.filterData;
});

const _filters = ref({ ...props.filterData });
// console.log("filterData " + "-" + " ...", _filters.value, props.filterData);

let search_debounce: any = null;
const emit = defineEmits(["filtering"]);
watch(_filters.value, (val) => {
    if (search_debounce) clearTimeout(search_debounce);
    search_debounce = setTimeout(() => {
        emit("filtering", val);
    }, 1000);
});
// watch(_filters.value, (v) => console.log("watching _filters", v));
</script>
