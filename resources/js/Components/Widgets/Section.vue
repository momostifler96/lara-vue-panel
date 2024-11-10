<template>
    <section class="">
        <h4 v-if="title">{{ title }}</h4>
        <div :class="`grid gap-3 grid-cols-${grid_cols}`">
            <component v-for="(widget, i) in props.widgets" :is="widgets[widget.widget_type]" v-bind="widget"
                :key="`widget-${widget.widget_type}-${i}`" />
        </div>
    </section>
</template>
<script setup lang="ts">
import { inject } from "vue";

interface Props {
    title: string;
    widgets: any[];
    grid_cols: number;
}

const props = withDefaults(defineProps<Props>(), {
    grid_cols: 1,
});

const widgets = <{ [k: string]: any }>inject('lvp_widgets');
</script>
