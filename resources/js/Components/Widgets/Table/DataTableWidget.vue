<template>
  <FiltersGrid v-if="filter && filter_type == 'grid'" :options="filter" :filterData="filterData" :loading="false"
    @filtering="onFiltering" @reset="onResetFilter" />
  <LVPTable :data="data.items" :columns="columns" v-model:selected="seletedItems" :hasFooter="paginated" fixeLastColumns
    @dataEvent="execColAction($event.action, $event)">
    <template #t_leading>
      <TableGroupedActionMenu :actions="bulk_actions" @exec="execGroupAction" />
    </template>
    <template #t_action>
      <div class="flex gap-2">
        <TextField v-if="searchables.length > 0" v-model="_filter.search" class="w-full" placeholder="Search"
          :prefix="icons.search">
          <template #prefix>
            <span class="text-gray-400 w-5 h-5 flex-center" v-html="icons.search"></span>
          </template>
          <template #surfix>
            <button class="text-gray-400 w-5 h-5 hover:bg-gray-200 flex-center" @click="_filter.search = ''"
              v-html="icons.close"></button>
          </template>
        </TextField>
        <FiltersPopover v-if="filter && filter_type == 'popover'" :options="filter" :filterData="filterData"
          :loading="false" @filtering="onFiltering" @reset="onResetFilter" />
      </div>
    </template>
    <template #actions="{ column, item }">
      <template v-if="column.data.type == 'inline'">
        <div class="flex gap-2">
          <TableActionButton v-for="action in column.data.actions" :icon="action_icons[action.icon]"
            :label="action.label" :action="action" :color="action.color" :item="item"
            @click="$emit('action', action.action, item)" />
        </div>
      </template>
      <TableActionMenu v-else-if="column.data.type == 'dropdown'" :tableItem="item" :tableActions="column.data.actions"
        @exec="execAction($event, item)" />
    </template>
    <template #t_footer>
      <div class="flex items-center justify-between" v-if="paginated">
        <Pagination :total-items="data.pagination.total" :items-per-page="data.pagination.per_page"
          :modelValue="data.pagination.current_page" @update:modelValue="navigate" />
        <Select @update:modelValue="navigatePerpage" :modelValue="data.pagination.per_page" class="h-8 w-44"
          placeholder="Par page" :options="[5, 10, 20, 50, 100]" />
      </div>
    </template>
  </LVPTable>

  <DynamicFormModal v-bind="form_modal" @close="form_modal.show = false" :show="form_modal.show" />
</template>
<script setup lang="ts">
import icons, { TrashIcon, EditIcon, EyeIcon } from "lvp/svg_icons";
import Select from "lvp/Components/Forms/Select.vue";
import Pagination from "lvp/Components/Buttons/Pagination.vue";
import TableActionButton from "lvp/Components/Widgets/Table/TableActionButton.vue";
import TableActionMenu from "lvp/Components/Widgets/Table/TableActionMenu.vue";
import TableGroupedActionMenu from "lvp/Components/Widgets/Table/TableGroupedActionMenu.vue";
import { ref, reactive, computed, watch, inject } from "vue";
import LVPTable from "lvp/Components/Widgets/Table/TableWidget.vue";
import FiltersPopover from "lvp/Components/Widgets/Table/FiltersPopover.vue";
import FiltersGrid from "lvp/Components/Widgets/Table/FiltersGrid.vue";
import { SelectedItemsActions, SingleItemAction, TableColumn, TableFilter } from "lvp/Types";

import { router } from "@inertiajs/vue3";
import { useToast } from "lvp/Plugins/toast";
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";
import DynamicFormModal from "lvp/Components/Dialogs/DynamicFormModal.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import { showConfirmation } from "lvp/utils";
interface TableGroupAction {
  type: string;
  actions: {
    label: string;
    icon: string;
    color: string;
    action: string;
  }[];
}
interface TableData {
  items: [];
  pagination: {
    total_items: number;
    total: number;
    current_page: number;
    per_page: number;
    from: number;
    to: number;
    path: string;
  };
}
const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  fixe_last_column: {
    type: Boolean,
    required: true,
  },
  fixe_first_column: {
    type: Boolean,
    required: true,
  },
  paginated: {
    type: Boolean,
    required: true,
  },
  bulk_actions: {
    type: Object as () => TableGroupAction,
    required: true,
  },
  data: {
    type: Object as () => TableData,
    required: true,
  },
  filterData: {
    type: Object,
    required: true,
  },
  columns: {
    type: Array<TableColumn>,
    required: true,
  },
  filter: {
    type: Object as () => TableFilter,
    required: true,
  },
  routes: {
    type: Object,
    required: true,
  }, searchables: {
    type: Object,
    required: true,
  },
  filter_type: {
    type: Object as () => 'popover' | 'grid',
    required: true,
  },
});
const emit = defineEmits([
  "delete",
  "search",
  "filtering",
  "edit",
  "action",
  "groupAction",
  "dataEvent",
]);

const queryString = new URLSearchParams(document.location.search);

const _filter = ref({
  search: queryString.get("search") ?? "",
});
const filterData = ref<{ [k: string]: string }>({});

queryString.forEach((value, key) => {
  filterData.value[key] = value;
});
const execfilters = (filters: any) => {
  const _filters = Object.keys(filters);
  for (let index = 0; index < _filters.length; index++) {
    queryString.set(
      _filters[index],
      Array.isArray(filters[_filters[index]])
        ? filters[_filters[index]].join("|")
        : filters[_filters[index]]
    );
  }
  queryString.set("page", "1");
  router.get("?" + queryString.toString());
};
const onFiltering = (filter_data: any) => {
  execfilters(filter_data);
};
const onResetFilter = () => {
  router.get("?page=1");
};
const action_icons = <Record<string, any>>{
  edit: EditIcon,
  view: EyeIcon,
  delete: TrashIcon,
};

const navigate = (page: number) => {
  queryString.set("page", page.toString());
  router.get("?" + queryString.toString());
};
const navigatePerpage = () => { };
let search_debounce: any = null;


watch(
  () => _filter.value.search,
  (val) => {
    if (search_debounce) clearTimeout(search_debounce);
    search_debounce = setTimeout(() => {
      queryString.set("search", val);
      router.get("?" + queryString.toString());
    }, 1000);
  }
);
const seletedItems = ref([]);

//------------------------—

const datatable_item_actions = <SingleItemAction>(
  inject("lvp.actions.datatable.item")
);

const datatable_item_col_actions_ = <SingleItemAction>(
  inject("lvp.actions.datatable.item_col")
);
const datatable_item_col_actions = {
  update_col: (opt: any) => {
    opt.router.post(route(opt.route_list.exec_actions), opt.data);
  },
  ...datatable_item_col_actions_
}
const datatable_selected_item_actions = <SelectedItemsActions>(
  inject("lvp.actions.datatable.bulk")
);

//------------------Confirmation modal-----------
const confirmation_modal = reactive({
  show: false,
  title: "create",
  body: "create",
  has_password: false,
  cancel_button_label: "Cancel",
  confirm_button_label: "Confirm",
  onResponse: (rsp: boolean) => { },
});
//------------------Confirmation modal-----------
const form_modal = reactive({
  show: false,
  title: "create",
  description: "create",
  fields: [],
  has_password: false,
  cancel_button_label: "Cancel",
  submit_button_label: "Confirm",
  onCancel: () => { },
  onSubmit: (data: { [k: string]: any }) => {

  },
});
//------------------Actions-----------


const execColAction = (action: string, data: any) => {
  datatable_item_col_actions[action]({
    showConfirmation: (option) => {
      confirmation_modal.title = option.title;
      confirmation_modal.body = option.body;
      confirmation_modal.cancel_button_label = option.cancel_button_label;
      confirmation_modal.confirm_button_label = option.confirm_button_label;
      confirmation_modal.has_password = option.has_password;
      confirmation_modal.onResponse = (rsp: boolean, password: string) => {
        if (rsp) {
          option.onConfirm(password);
        } else {
          option.onCancel();
        }
        confirmation_modal.show = false;
        confirmation_modal.title = "";
        confirmation_modal.body = "";
      };
      confirmation_modal.show = true;
    },
    data,
    showToast: useToast,
    route_list: props.routes,
    router: router,
  });
}
const table_single_item_actions = <SingleItemAction>{
  edit: ({ route_list, item }) => {
    emit("edit", item);
  },
  view: (opt: any) => {
    opt.router.get(route(opt.route_list.show, { id: opt.item.id }));
  },
  delete: (opt) => {
    opt.showConfirmation({
      title: "Delete",
      body: "Are you sure you want to delete this item?",
      onConfirm: () => {
        emit("delete", opt.item);
        // router.delete(route(opt.route_list.delete, { id: opt.item.id }));
      },
    });
  },
  "resource.delete": (opt) => {
    opt.showConfirmation({
      title: "Delete",
      body: "Are you sure you want to delete this item?",
      onConfirm: (password: string) => {
        opt.router.delete(route(opt.route_list.delete, { id: opt.item.id }));
      },
    });
  },
  update_col: (opt: any) => {
    opt.router.post(route(opt.route_list.exec_actions, { id: opt.item.id }));
  },
  ...datatable_item_actions,
};

const execAction = (action: string, item: any) => {
  table_single_item_actions[action]({
    showConfirmation: showConfirmation,

    item,
    showToast: useToast,
    route_list: props.routes,
    router: router,
  });
};

//------------------Actions-----------

const table_selected_items_actions = <SelectedItemsActions>{
  delete: (opt: any) => {
    opt.showConfirmation({
      title: "Delete",
      body: "Are you sure you want to delete this items?",
      onConfirm: () => {
        router.delete(route(opt.route_list.delete, { id: opt.selected_items_ids.join(',') }));
      },
    });
  },
  ...datatable_selected_item_actions,
};

const execGroupAction = (action: string, items: any[]) => {
  table_selected_items_actions[action]({
    showConfirmation: showConfirmation,
    showFormModal: (option: any) => {
      form_modal.title = option.title;
      form_modal.description = option.description;
      form_modal.cancel_button_label = option.cancel_button_label;
      form_modal.submit_button_label = option.submit_button_label;
      form_modal.has_password = option.has_password;
      form_modal.fields = option.fields;
      form_modal.onSubmit = option.onSubmit;
      form_modal.show = true;
    },
    selected_items_ids: seletedItems.value,
    selected_items: seletedItems.value,
    showToast: useToast,
    route_list: props.routes,
    router: router,
  });
};
</script>
