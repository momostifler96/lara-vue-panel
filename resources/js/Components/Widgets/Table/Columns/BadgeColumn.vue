<template>
    <span
        :class="colors[column.value_colors[data]]"
        class="px-2 py-1 font-bold capitalize bg-opacity-10 lvp-button-border-radius"
        >{{ data }}</span
    >
</template>
<script setup lang="ts">
const props = defineProps<{
    field: string;
    data: string;
    color: string;
    column: any;
}>();

const colors = <Record<string, string>>{
    primary: "text-lvp-primary bg-lvp-primary",
    success: "text-lvp-success bg-lvp-success",
    info: "text-lvp-info bg-lvp-info",
    warning: "text-lvp-warn bg-lvp-warn",
    danger: "text-lvp-danger bg-lvp-danger",
    disable: "text-gray-300 bg-gray-300",
};
console.log("badge props", props);
</script>
