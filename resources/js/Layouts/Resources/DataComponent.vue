<template>
  <component :is="dataComponents[props.type]" v-bind="props.props" :routes="routes" key="data-widget" class="col-span-3"
    @dataEvent="exectDataEvent" @bulkAction="execBulkAction" @action="emit('edit', $event)" @delete="deteleItem" />
</template>
<script setup lang="ts">
import DataTableWidget from "lvp/Components/Widgets/Table/DataTableWidget.vue";
import { router } from "@inertiajs/vue3";
import { inject } from "vue";
import DataGridWidget from "lvp/Components/Widgets/DataGridWidget.vue";

const props = defineProps({
  type: {
    type: String,
    required: true,
  },
  props: {
    type: Object,
    required: true,
  },
  routes: {
    type: Object,
    required: true,
  },
});
const dataComponents = <{ [k: string]: any }>{
  "data-table": DataTableWidget,
  "data-grid": DataGridWidget,
};
const emit = defineEmits(["edit", "submit"]);

//------------------Actions-----------



const deteleItem = (item: any) => {
  router.delete(route(props.routes.delete, { id: item.id }) as unknown as string);
};

const exectDataEvent = (event: any) => {
  router.post(route(props.routes.exec_actions, event) as unknown as string);
};


const execBulkAction = (event: any) => {
  router.post(route(props.routes.exec_actions, event) as unknown as string);
};
</script>
