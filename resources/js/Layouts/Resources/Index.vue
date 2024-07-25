<template>
    <PanelLayout :pageTitle="props.titles.index_page_title">
        <template #actions>
            <SimpleButton
                v-if="props.form_type == 'modal'"
                @click="createResource"
                ><span class="w-5 h-5" v-html="AddIcon"></span>
                <span class="hidden md:inline lg:inline">{{
                    props.titles.create_resource
                }}</span>
            </SimpleButton>
            <SimpleButton
                v-else-if="props.form_type == 'page'"
                button-type="link"
                :href="route(props.resources_routes.create)"
                ><span class="w-5 h-5" v-html="AddIcon"></span
                ><span class="hidden md:inline lg:inline">{{
                    props.titles.create_resource
                }}</span></SimpleButton
            >
        </template>
        <div
            v-if="props.before_data_widgets.length > 0"
            class="grid grid-cols-3 gap-3 mt-10 mb-10"
        >
            <component
                v-for="(widget, i) in props.before_data_widgets"
                :is="widgets_components[widget.widget_type]"
                v-bind="widget"
                :key="`widget-${widget.widget_type}-${i}`"
                :class="`col-span-${widget.col_span}`"
            />
        </div>
        <component
            v-for="(widget, i) in props.widgets"
            :is="widgets_components[widget.type]"
            v-bind="widget.data"
            :key="`${widget.type}-${i}`"
            @search="execAction"
            hasHeaderFilter
            @filtering="execfilters"
            :filterData="filterData"
            @action="execAction"
            @dataEvent="onDataEvent"
            @groupAction="execGroupAction"
        />
        <div
            v-if="props.after_data_widgets.length > 0"
            class="grid grid-cols-3 gap-3 mt-10 mb-10"
        >
            <component
                v-for="(widget, i) in props.after_data_widgets"
                :is="widgets_components[widget.widget_type]"
                v-bind="widget"
                :key="`widget-${widget.widget_type}-${i}`"
                :class="`col-span-${widget.col_span}`"
            />
        </div>
    </PanelLayout>
    <ConfirmationModal
        :show="confir_modal.show"
        icon="delete"
        :title="confir_modal.title"
        :body="confir_modal.body"
        @onResponse="confir_modal.onResponse"
    />

    <FormModal
        @close="closeFormModal"
        :titles="props.titles"
        :show="form_modal.show"
        :formFields="props.form_fields.form_render"
        :formData="form_modal.form_data"
        :routes_names="props.resources_routes"
        :action="form_modal.action"
        :submitLabel="form_modal.submit_label"
        :cancelLabel="form_modal.cancel_label"
        :modalTitle="form_modal.title"
        @submit="submitFormTata"
    />
</template>
<script setup lang="ts">
import PanelLayout from "../Partials/PanelLayout.vue";
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";
import FormModal from "./ModalForm.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, inject, reactive, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import DataTable from "lvp/Components/Widgets/Table/DataTable.vue";
import { AddIcon } from "lvp/helpers/lvp_icons";
import type {
    TableData,
    ActionsList,
    SelectedActionsList,
} from "lvp/helpers/types";
import { useToast } from "lvp/Plugins/toast";
import { ResourceRoutes, ResourceTitles } from "lvp/PropsTypes";
import LineChart from "lvp/Components/Widgets/Chats/LineChart.vue";
import BaseChart from "lvp/Components/Widgets/Chats/BaseChart.vue";
interface Titles {
    create_new: string;
    edit_item: string;
    delete_confirmation_body: string;
    delete_confirmation_title: string;
    index_page_title: string;
    label: string;
    plural_label: string;
}

interface Widget {
    type: string;
    data: { [k: string]: any };
}

interface ResourceIndexPage {
    titles: ResourceTitles;
    resources_routes: ResourceRoutes;
    data: Object | any;
    table_filters: any;
    widgets: Widget[];
    before_data_widgets: any[];
    after_data_widgets: any[];
    form_fields: any;
    form_type: "modal" | "page";
}

const props = computed(() => {
    return usePage().props as unknown as ResourceIndexPage;
});

//------------------Widgets-----------
const widgets_components = <{ [key: string]: any }>{
    data_table: DataTable,
    chart: BaseChart,
};
//------------------Widgets-----------
//------------------Table envent-----------

interface DataEvent {
    event: string;
    field: string;
    value: any[];
    old_value: any[];
    item_id: string;
    has_confirmation: boolean;
    confirmation_body?: string;
    confirmation_title?: string;
}
const onDataEvent = (event_data: DataEvent) => {
    if (event_data.has_confirmation) {
        confir_modal.title = event_data.confirmation_title ?? "Confirmation";
        confir_modal.body =
            event_data.confirmation_body ?? "Are you sur to exec this action";
        confir_modal.show = true;
        confir_modal.onResponse = (rsp: boolean) => {
            if (rsp) {
                router.post(route(props.value.resources_routes["it-update"]), {
                    resource_id: event_data.item_id,
                    field: event_data.field,
                    value: event_data.value,
                    old_value: event_data.old_value,
                    action: event_data.event + "-update-" + event_data.field,
                });
            }
            confir_modal.show = false;
        };
    } else {
        router.post(route(props.value.resources_routes["it-update"]), {
            resource_id: event_data.item_id,
            field: event_data.field,
            value: event_data.value,
            old_value: event_data.old_value,
            action: event_data.event + "-update-" + event_data.field,
        });
    }
};
//------------------Widgets-----------
//------------------Item form modal-----------
const form_modal = reactive<{
    show: boolean;
    title: string;
    submit_label: string;
    cancel_label: string;
    action: string;
    form_data: { [k: string]: any };
}>({
    show: false,
    title: "create",
    action: "create",
    cancel_label: "Cancel",
    submit_label: "Submit",
    form_data: {},
});

const closeFormModal = () => {
    form_modal.action = "create";
    form_modal.show = false;
    form_modal.title = props.value.titles.form_titles["create"].title;
    form_modal.submit_label =
        props.value.titles.form_titles["create"].submit_button;
    form_modal.cancel_label =
        props.value.titles.form_titles["create"].cancel_button;
    form_modal.form_data = props.value.form_fields.form_data;
};

const createResource = () => {
    if (props.value.form_fields == "page") {
        router.get(route(props.value.resources_routes.create));
    } else {
        form_modal.action = "create";
        form_modal.title = props.value.titles.form_titles["create"].title;
        form_modal.submit_label =
            props.value.titles.form_titles["create"].submit_button;
        form_modal.cancel_label =
            props.value.titles.form_titles["create"].cancel_button;
        form_modal.form_data = props.value.form_fields.form_data;

        form_modal.show = true;
    }
};

const editResource = (item: any) => {
    if (props.value.form_type == "page") {
        router.get(route(props.value.resources_routes.edit, item.id));
    } else {
        form_modal.action = "edit";
        form_modal.title = props.value.titles.form_titles["edit"].title;
        form_modal.submit_label =
            props.value.titles.form_titles["edit"].submit_button;
        form_modal.cancel_label =
            props.value.titles.form_titles["create"].submit_button;
        form_modal.form_data.id = item.id;
        const form_fields = Object.keys(props.value.form_fields.form_data);
        for (let index = 0; index < form_fields.length; index++) {
            form_modal.form_data[form_fields[index]] = item[form_fields[index]];
        }
        form_modal.show = true;
    }
};

const submitFormTata = () => {
    const route_path = route(
        form_modal.action == "create"
            ? props.value.resources_routes.store
            : props.value.resources_routes.update
    );
    router.post(route_path, form_modal.form_data);
};
//------------------Item form modal-----------
//------------------Confirmation modal-----------
const confir_modal = reactive({
    show: false,
    title: "create",
    body: "create",
    cancel_button_label: "Cancel",
    confirm_button_label: "Confirm",
    onResponse: (rsp: boolean) => {},
});

//------------------Confirmation modal-----------
//------------------Single actions-----------

const single_item_custom_actions = <ActionsList>(
    inject("lvp_single_item_actions")
);

const table_actions_methods = <ActionsList>{
    edit: ({ route_list, item }) => {
        if (props.value.form_type == "modal") {
            editResource(item);
        } else {
            router.get(route(route_list.edit, item.id));
        }
    },
    view: (item: any) => {
        // router.get(item.view);
    },
    delete: ({ item, route_list, router }) => {
        confir_modal.show = true;
        confir_modal.title = "Delete";
        confir_modal.body = "Are you sure you want to delete this item?";
        confir_modal.onResponse = (result) => {
            if (result) {
                router.delete(route(route_list.delete, { id: item.id }));
            }
            confir_modal.show = false;
            confir_modal.title = "";
            confir_modal.body = "";
        };
    },
    ...single_item_custom_actions,
};

const execAction = (action: string, item: any) => {
    table_actions_methods[action]({
        confirmationModal: (option) => {
            confir_modal.title = option.title;
            confir_modal.body = option.body;
            confir_modal.cancel_button_label = option.cancel_button_label;
            confir_modal.confirm_button_label = option.confirm_button_label;
            confir_modal.onResponse = option.onResponse;
            confir_modal.show = option.show;
        },
        item,
        showToast: useToast,
        route_list: props.value.resources_routes,
        router: router,
    });
};

//------------------Single actions-----------
//------------------Group actions-----------
const selected_items_custom_actions = <SelectedActionsList>(
    inject("lvp_selected_items_actions")
);
const group_actions_methods = <SelectedActionsList>{
    delete: ({ selected_items }) => {
        confir_modal.show = true;
        confir_modal.title = props.value.titles.delete_confirmation_title;
        confir_modal.body = props.value.titles.delete_confirmation_body;
        confir_modal.onResponse = (result) => {
            if (result) {
                router.delete(
                    route(props.value.resources_routes.delete, {
                        id: selected_items,
                    })
                );
            }
            confir_modal.show = false;
            confir_modal.title = "";
            confir_modal.body = "";
        };
    },
    // "export-exel": (items: []) => {},
    // "export-csv": (items: []) => {},
    ...selected_items_custom_actions,
};
function queryStringToObject(queryString: any) {
    const params = new URLSearchParams(queryString);
    const result = <Record<string, string | string[]>>{};

    params.forEach((value, key) => {
        if (result[key]) {
            // If the key already exists, convert it to an array if it isn't one already, then push the new value
            if (!Array.isArray(result[key])) {
                result[key] = [result[key]];
            }
            result[key].push(value);
        } else {
            result[key] = value;
        }
    });

    return result;
}
const queryString = new URLSearchParams(document.location.search);
const filterData = ref<{ [k: string]: string }>({});

queryString.forEach((value, key) => {
    filterData.value[key] = value;
});
const execfilters = (filters: any) => {
    const _filters = Object.keys(filters);
    for (let index = 0; index < _filters.length; index++) {
        queryString.set(_filters[index], filters[_filters[index]]);
    }
    router.get("?" + queryString.toString());
};

const execGroupAction = (action: string, items: any) => {
    group_actions_methods[action]({
        selected_items: items,
        showToast: useToast,
        route_list: props.value.resources_routes,
        router: router,
        confirmationModal: (option) => {
            confir_modal.title = option.title;
            confir_modal.body = option.body;
            confir_modal.cancel_button_label = option.cancel_button_label;
            confir_modal.confirm_button_label = option.confirm_button_label;
            confir_modal.onResponse = option.onResponse;
            confir_modal.show = option.show;
        },
    });
};
//------------------Group actions-----------
</script>
