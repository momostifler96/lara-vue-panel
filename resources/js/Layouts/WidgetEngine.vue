<template>
    <div v-if="props.widgets.length > 0" :class="`grid grid-cols-${props.cols} gap-${props.gap} mb-10`">
        <component v-for="(widget, i) in props.widgets" :key="`widget-${widget.type}-${i}`"
            :is="widgets_components[widget.type]" v-bind="widget.props" />
    </div>
</template>
<script setup lang="ts">
import BaseChart from 'lvp/Components/Widgets/Chats/BaseChart.vue';
import FormWidget from 'lvp/Components/Widgets/FormWidget.vue';
import DataTableWidget from 'lvp/Components/Widgets/Table/DataTableWidget.vue';

const props = defineProps({
    widgets: {
        type: Array as () => any[],
        required: true,
    }, cols: {
        type: Number,
        default: 1,
    }, gap: {
        type: Number,
        default: 3,
    },
});

const widgets_components = <{ [key: string]: any }>{
    "data-table": DataTableWidget,
    chart: BaseChart,
    form: FormWidget,
};
</script>