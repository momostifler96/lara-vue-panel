<template>
    <section class="">
        <h4 v-if="title">{{ title }}</h4>
        <div :class="`grid gap-3 grid-cols-${grid_cols}`">
            <component
                v-for="(widget, i) in props.widgets"
                :is="widgets[widget.widget_type]"
                v-bind="widget"
                :key="`widget-${widget.widget_type}-${i}`"
            />
        </div>
    </section>
</template>
<script setup lang="ts">
import { inject } from "vue";
const props = defineProps({
    widgets: Array,
    title: String,
    filled: Boolean,
    grid_cols: {
        type: Number,
        default: 1,
    },
});

const widgets = inject("lvp_widgets");
</script>
