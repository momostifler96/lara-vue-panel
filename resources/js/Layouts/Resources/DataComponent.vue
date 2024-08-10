<template>
  <component
    :is="dataComponents[props.type]"
    v-bind="props.props"
    :routes="routes"
    key="data-widget"
    class="col-span-3"
    @edit="emit('edit', $event)"
    @delete="deteleItem"
  />
</template>
<script setup lang="ts">
import DataTableWidget from "lvp/Components/Widgets/Table/DataTableWidget.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  type: {
    type: String,
    required: true,
  },
  props: {
    type: String,
    required: true,
  },
  routes: {
    type: Object,
    required: true,
  },
});
const dataComponents = <{ [k: string]: any }>{
  "data-table": DataTableWidget,
  "data-grid": DataTableWidget,
};
const emit = defineEmits(["edit", "submit"]);

//------------------Actions-----------

const deteleItem = (item: any) => {
  router.delete(route(props.routes.delete, { id: item.id }));
};
</script>
