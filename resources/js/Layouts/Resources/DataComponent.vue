<template>
  <component :is="dataComponents[props.type]" v-bind="props.props" :routes="routes" key="data-widget" class="col-span-3"
    @dataEvent="exectDataEvent" @bulkAction="execBulkAction" @edit="emit('edit', $event)" @delete="deteleItem" />
</template>
<script setup lang="ts">
import DataTableWidget from "lvp/Components/Widgets/Table/DataTableWidget.vue";
import { router } from "@inertiajs/vue3";
import { ActionsList } from "lvp/Types";
import { inject } from "vue";

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
  "data-grid": DataTableWidget,
};
const emit = defineEmits(["edit", "submit"]);

//------------------Actions-----------


const actions = <ActionsList>inject("lvp.actions.datatable.item");

const deteleItem = (item: any) => {
  router.delete(route(props.routes.delete, { id: item.id }));
};

const exectDataEvent = (event: any) => {
  router.post(route(props.routes.exec_actions, event));
};


const execBulkAction = (event: any) => {
  router.post(route(props.routes.exec_actions, event));
};
</script>
