<template>
    <SwitchToggle :modelValue="val" @update:modelValue="onSelect" />
</template>
<script setup lang="ts">
import SwitchToggle from "lvp/Components/Forms/SwitchToggle.vue";
import { computed } from "vue";

const props = defineProps<{
    field: string;
    data: boolean;
    column: any;
}>();
console.log("Toggle column data", props.data, props.column);
const emit = defineEmits(["dataEvent"]);
const onSelect = (value: any) => {
    console.log("Toggle column  change", value);
    emit("dataEvent", {
        event: "change_toogle",
        has_confirmation: props.column.has_confirmation,
        confirmation_title: props.column.confirmation_title,
        confirmation_body: props.column.confirmation_body,
        field: props.field,
        value: value ? props.column.true_value : props.column.false_value,
        old_value: props.data,
    });
};

const val = computed(() => {
    return props.data == props.column.true_value;
});
</script>
