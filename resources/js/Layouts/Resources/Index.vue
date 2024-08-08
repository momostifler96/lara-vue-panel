<template>
  <PanelLayout :pageTitle="props.page_titles.title">
    <template #actions>
      <slot name="actions" />
      <SimpleButton @click="creatResource">{{
        props.labels.create
      }}</SimpleButton>
    </template>

    <div
      v-if="props.before_data_widgets.length > 0"
      class="grid grid-cols-3 gap-3 mt-10 mb-10 tt"
    >
      <component
        v-for="(widget, i) in props.before_data_widgets"
        :is="widgets_components[widget.widget_type]"
        v-bind="widget"
        :key="`widget-${widget.widget_type}-${i}`"
        @edit="edit"
        :class="`col-span-${widget.col_span}`"
      />
    </div>
    <div class="">
      <DataComponent
        v-bind="props.data_widget"
        key="data-widget"
        :routes="props.routes"
        @edit="editResource"
      />
    </div>

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
  <ModalForm
    @close="form_modal.show = false"
    :show="form_modal.show"
    :defaultData="form_modal.data"
    :action="form_modal.action"
    v-bind="props.modal_form"
  />
</template>
<script setup lang="ts">
import PanelLayout from "../Partials/PanelLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, inject, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import DataTableWidget from "lvp/Components/Widgets/Table/DataTableWidget.vue";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import DataComponent from "./DataComponent.vue";

import { ResourceRoutes, ResourceTitles } from "lvp/PropsTypes";
import LineChart from "lvp/Components/Widgets/Chats/LineChart.vue";
import BaseChart from "lvp/Components/Widgets/Chats/BaseChart.vue";
import FormWidget from "lvp/Components/Widgets/FormWidget.vue";
import ModalForm from "./ModalForm.vue";
interface Titles {
  title: string;
  meta_title: string;
  description_title: string;
}

interface Widget {
  type: string;
  data: { [k: string]: any };
}

interface ResourceIndexPage {
  page_titles: Titles;
  labels: any;
  modal_labels: {
    create: {
      title: string;
      submit: string;
      cancel: string;
    };
    edit: {
      title: string;
      submit: string;
      cancel: string;
    };
    delete: {
      title: string;
      confirm: string;
      cancel: string;
    };
  };
  data_widget: any;
  routes: ResourceRoutes;
  data: Object | any;
  table_filters: any;
  widgets: Widget[];
  before_data_widgets: any[];
  after_data_widgets: any[];
  form_fields: any;
  modal_form: any;
  form_type: "modal" | "page";
}

const props = computed(() => {
  return usePage().props as unknown as ResourceIndexPage;
});
//------------------Widgets-----------
const widgets_components = <{ [key: string]: any }>{
  "data-table": DataTableWidget,
  chart: BaseChart,
  form: FormWidget,
};
const form_modal = reactive({
  show: false,
  action: "create",
  data: null,
});

const creatResource = () => {
  form_modal.show = true;
  form_modal.data = null;
  form_modal.action = "create";
};
const editResource = (item: any) => {
  form_modal.show = true;
  form_modal.data = { ...item.props, id: item.id };
  form_modal.action = "edit";
};

//------------------Widgets-----------
</script>
