<template>
  <component
    :is="dataComponents[props.widget_type]"
    v-bind="$attrs"
    key="data-widget"
    class="col-span-3"
    @action="execAction"
    @edit="emit('edit', $event)"
  />
  <ConfirmationModal
    :show="confirmation_modal.show"
    icon="delete"
    :title="confirmation_modal.title"
    :body="confirmation_modal.body"
    @onResponse="confirmation_modal.onResponse"
  />
</template>
<script setup lang="ts">
import DataTableWidget from "lvp/Components/Widgets/Table/DataTableWidget.vue";
import { ActionsList } from "lvp/helpers/types";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { useToast } from "lvp/Plugins/toast";
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";

const props = defineProps({
  widget_type: {
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
const editItem = (action: any, item: any) => {
  emit(action, item);
  //   console.log("editItem", action, item);
};
//------------------Confirmation modal-----------
const confirmation_modal = reactive({
  show: false,
  title: "create",
  body: "create",
  cancel_button_label: "Cancel",
  confirm_button_label: "Confirm",
  onResponse: (rsp: boolean) => {},
});
//------------------Actions-----------

const table_actions_methods = <ActionsList>{
  edit: ({ route_list, item }) => {
    emit("edit", item);
  },
  view: (item: any) => {
    // router.get(item.view);
  },
  delete: ({ item, route_list, router }) => {
    confirmation_modal.show = true;
    confirmation_modal.title = "Delete";
    confirmation_modal.body = "Are you sure you want to delete this item?";
    confirmation_modal.onResponse = (result) => {
      if (result) {
        router.delete(route(route_list.delete, { id: item.id }));
      }
      confirmation_modal.show = false;
      confirmation_modal.title = "";
      confirmation_modal.body = "";
    };
  },
};

const execAction = (action: string, item: any) => {
  table_actions_methods[action]({
    confirmationModal: (option) => {
      confirmation_modal.title = option.title;
      confirmation_modal.body = option.body;
      confirmation_modal.cancel_button_label = option.cancel_button_label;
      confirmation_modal.confirm_button_label = option.confirm_button_label;
      confirmation_modal.onResponse = option.onResponse;
      confirmation_modal.show = option.show;
    },
    item,
    showToast: useToast,
    route_list: props.routes,
    router: router,
  });
};
</script>
