<template>
    <div class="p-3 bg-white shadow lvp-widget-border-radius">
        <div class="flex mb-3">
            <span
                v-if="icon"
                class="w-10 h-10 p-2 rounded-full bg-opacity-20"
                :style="`color:${color};background-color:${color}37;`"
                v-html="lvp_icons[icon]"
            >
            </span>
        </div>
        <div class="">
            <h4 class="text-xl font-bold">{{ value }}</h4>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">{{ title }}</span>
                <span class="text-green-700" :style="`color: ${percentColor};`"
                    >{{ percent }}%</span
                >
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { inject } from "vue";

const props = defineProps({
    icon: String,
    title: {
        type: String,
        default: "Title",
    },
    value: {
        type: String,
        default: "",
    },
    percent: {
        type: Number,
        default: 45,
    },
    color: {
        type: String,
        default: "#0193EE",
    },
    percentColor: {
        type: String,
        default: "#01EE58",
    },
});

const lvp_icons = inject("lvp_icons");
</script>
