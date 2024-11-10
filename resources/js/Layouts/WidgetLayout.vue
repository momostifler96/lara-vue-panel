<template>
    <component v-if="_widgets" v-for="(widget, i) in widgets" class="mt-5" :is="_widgets[widget.widget_type] ?? 'div'"
        :key="`widget-${widget.widget_type}-${i}`" />
</template>
<script setup lang="ts">
import { inject } from "vue";
defineProps({
    widgets: {
        type: Array<any>,
        required: true,
    },
});
const _widgets = inject<{ [k: string]: any }>("lvp_widgets");
</script>
