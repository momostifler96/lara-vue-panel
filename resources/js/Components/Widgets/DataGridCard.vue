<template>
    <component :is="data_components[props.card_type]" v-bind="props.item.props" :item="props.item" />
</template>
<script setup lang="ts">
import { computed, inject, ref } from "vue";
import { useToast } from "lvp/Plugins/toast";
import { router } from "@inertiajs/vue3";


const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    card_type: {
        type: Array,
        required: true,
    },
});
const data_components = <{ [k: string]: any }>inject("lvp_data_grid_cards");

</script>