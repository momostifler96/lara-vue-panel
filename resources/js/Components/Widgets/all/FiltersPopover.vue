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
                        <div
                            class="flex items-center justify-between px-2 text-sm"
                        >
                            <span>Filters</span>
                            <button class="text-lvp-danger" type="button">
                                Reset all
                            </button>
                        </div>
                        <label class="gap-2 lvp-popover-link" for="f-title">
                            <input
                                id="f-title"
                                type="checkbox"
                                class="lvp-checkbox"
                            />
                            <span>Title</span>
                        </label>
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

const props = defineProps({
    filters: Object,
    loading: Boolean,
});
</script>
