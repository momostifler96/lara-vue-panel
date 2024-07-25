<template>
    <component
        v-for="(widget, i) in widgets"
        class="mt-5"
        :is="_widgets[widget.widget_type]"
        v-bind="widget"
        :key="`widget-${widget.widget_type}-${i}`"
    />
</template>
<script setup lang="ts">
import { inject } from "vue";
defineProps({
    widgets: {
        type: Array<any>,
        required: true,
    },
});
const _widgets = inject<Record<string, any>>("lvp_widgets");
console.log("_widgets", _widgets);
</script>
