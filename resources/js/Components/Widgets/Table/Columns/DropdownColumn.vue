<template>
    <Dropdown
        :options="column.options"
        :modelValue="data"
        option-value="value"
        option-label="label"
        @change="onSelect"
        class="w-full"
    />
</template>
<script setup lang="ts">
import Dropdown from "primevue/dropdown";

const props = defineProps<{
    field: string;
    data: string;
    column: any;
}>();
const emit = defineEmits(["dataEvent"]);
const onSelect = (value: any) => {
    emit("dataEvent", {
        event: "change_dropdown",
        has_confirmation: props.column.has_confirmation,
        confirmation_title: props.column.confirmation_title,
        confirmation_body: props.column.confirmation_body,
        field: props.field,
        value: value.value,
        old_value: props.data,
    });
};
</script>
