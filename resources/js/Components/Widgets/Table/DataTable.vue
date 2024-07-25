<template>
    <LVPTable
        :data="data.items"
        :columns="columns"
        v-model:selected="seletedItems"
        :hasHeader="false"
        :hasFooter="paginated"
        fixeLastColumns
        @dataEvent="$emit('dataEvent', $event)"
    >
        <template #t_leading>
            <TableGroupedActionMenu
                v-if="seletedItems.length > 0"
                :actions="group_action"
                @exec="execGroupAction"
            />
        </template>
        <template #t_action>
            <div class="flex gap-2">
                <SearchTextField
                    v-if="hasSearchable"
                    v-model="_filter.search"
                />
                <FiltersPopover
                    v-if="filter"
                    :options="filter"
                    :filterData="filterData"
                    :loading="false"
                    @filtering="onFiltering"
                />
            </div>
        </template>
        <template #actions="{ column, item }">
            <template v-if="column.data.type == 'inline'">
                <div class="flex gap-2">
                    <TableActionButton
                        v-for="action in column.data.actions"
                        :icon="action_icons[action.icon]"
                        :label="action.label"
                        :action="action"
                        :color="action.color"
                        :item="item"
                        @click="$emit('action', action.action, item)"
                    />
                </div>
            </template>
            <TableActionMenu
                v-else-if="column.data.type == 'dropdown'"
                :tableItem="item"
                :tableActions="column.data.actions"
                @exec="
                    (action) => {
                        emit('action', action, item);
                    }
                "
            />
        </template>
        <template #t_footer>
            <div class="flex items-center justify-between" v-if="paginated">
                <Pagination
                    :total-items="data.pagination.total"
                    :items-per-page="data.pagination.per_page"
                    :modelValue="data.pagination.current_page"
                    @update:modelValue="navigate"
                />
                <Select
                    @update:modelValue="navigatePerpage"
                    :modelValue="data.pagination.per_page"
                    class="h-8 w-44"
                    placeholder="Par page"
                    :options="[5, 10, 20, 50, 100]"
                />
            </div>
        </template>
    </LVPTable>
</template>
<script setup lang="ts">
import { TrashIcon, PencilIcon, EyeIcon } from "@heroicons/vue/24/solid";
import Select from "lvp/Components/Forms/Select.vue";
import Pagination from "lvp/Components/Buttons/Pagination.vue";
import TableActionButton from "lvp/Components/Widgets/Table/TableActionButton.vue";
import TableActionMenu from "lvp/Components/Widgets/Table/TableActionMenu.vue";
import TableGroupedActionMenu from "lvp/Components/Widgets/Table/TableGroupedActionMenu.vue";
import { router } from "@inertiajs/vue3";
import { ref, reactive, computed, watch, onMounted } from "vue";
import LVPTable from "lvp/Components/Widgets/Table/TableWidget.vue";
import FiltersPopover from "lvp/Components/Widgets/Table/FiltersPopover.vue";
import SearchTextField from "lvp/Components/Forms/SearchTextField.vue";
import TableWidget from "lvp/Components/Widgets/Table/TableWidget.vue";
import TextColumn from "lvp/Components/Widgets/Table/Columns/TextColumn.vue";
import ImageColumn from "lvp/Components/Widgets/Table/Columns/ImageColumn.vue";
import DropdownColumn from "lvp/Components/Widgets/Table/Columns/DropdownColumn.vue";
import { inject } from "vue";

import ToggleColumn from "lvp/Components/Widgets/Table/Columns/ToggleColumn.vue";
import { TableColumn, TableFilter } from "lvp/Types";

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
    group_action: {
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
const table_column_widget = {
    text: TextColumn,
    image: ImageColumn,
    dropdown: DropdownColumn,
    toggle: ToggleColumn,
};
console.log("filter data columns...", props.columns, props.data);
const queryString = new URLSearchParams(document.location.search);

const _filter = ref({
    search: queryString.get("search") ?? "",
});

const onFiltering = (filter_data: any) => {
    emit("filtering", filter_data);
};
const action_icons = <Record<string, any>>{
    edit: PencilIcon,
    view: EyeIcon,
    delete: TrashIcon,
};
const confirmation_modal = reactive<Record<string, any>>({
    show: false,
    title: "",
    body: "",
    current_id: "",
    onConfirm: () => {},
    onCancel: () => {
        confirmation_modal.show = false;
        confirmation_modal.title = "";
        confirmation_modal.body = "";
        confirmation_modal.current_id = "";
    },
});

const execGroupAction = (action: any) => {
    emit("groupAction", action, seletedItems.value);
};

const navigate = (page: number) => {
    queryString.set("page", page.toString());
    router.get("?" + queryString.toString());
};

let search_debounce: any = null;
const hasSearchable = computed(() => {
    return props.columns.some((col) => {
        return col.searchable;
    });
});
watch(
    () => _filter.value.search,
    (val) => {
        if (search_debounce) clearTimeout(search_debounce);
        search_debounce = setTimeout(() => {
            search(val);
        }, 1000);
    }
);
const seletedItems = ref([]);
const navigatePerpage = (num: number) => {
    queryString.set("perPage", num.toString());
    queryString.set("page", "1");
    router.get("?" + queryString.toString());
};
const search = (val: string) => {
    queryString.set("search", val);
    queryString.set("page", "1");
    router.get("?" + queryString.toString());
};
</script>
