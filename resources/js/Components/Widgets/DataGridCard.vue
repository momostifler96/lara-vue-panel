<script setup lang="ts">
import { computed, inject, ref } from "vue";
import { useToast } from "lvp/Plugins/toast";
import { router } from "@inertiajs/vue3";

// Define the type for card types
type CardType = 'type1' | 'type2' | 'type3'; // Replace with actual card types

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    card_type: {
        type: String as () => CardType, // Ensure card_type is a specific string type
        required: true,
    },
});

const data_components = <{ [k in CardType]: any }>inject("lvp_data_grid_cards");

</script>

<template>
    <component :is="data_components[props.card_type]" v-bind="props.item.props" :item="props.item" />
</template>