<template>
    <button :class="btn_class">{{ props.label }}</button>
</template>
<script setup lang="ts">

const props = defineProps({
    target: {
        type: Object,
        required: true,
    },
    url: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    btn_class: {
        type: String,
        required: true,
    },
    icon_position: {
        type: String as () => "left" | "right",
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
})

</script>