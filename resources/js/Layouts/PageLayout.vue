<template>
  <PanelLayout :pageTitle="props.page_titles.title">
    <template #actions>
      <slot name="actions" />
    </template>

    <div v-if="props.before_content_widgets.length > 0" class="grid grid-cols-3 gap-3 mt-10 mb-10 tt">
      <component v-for="(widget, i) in props.before_content_widgets" :is="widgets_components[widget.type]"
        v-bind="widget.props" :key="`widget-${widget.widget_type}-${i}`" :class="`col-span-${widget.col_span}`" />
    </div>
    <div class="">
      <slot />
    </div>

    <div v-if="props.after_content_widgets.length > 0" class="grid grid-cols-3 gap-3 mt-10 mb-10">
      <component v-for="(widget, i) in props.after_content_widgets" :is="widgets_components[widget.type]"
        v-bind="widget.props" :key="`widget-${widget.widget_type}-${i}`" :class="`col-span-${widget.col_span}`" />
    </div>
  </PanelLayout>
</template>
<script setup lang="ts">
import PanelLayout from "./Partials/PanelLayout.vue";
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, inject, reactive, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import DataTableWidget from "lvp/Components/Widgets/Table/DataTableWidget.vue";

import type {
  TableData,
  ActionsList,
  SelectedActionsList,
} from "lvp/helpers/types";
import { useToast } from "lvp/Plugins/toast";
import { ResourceRoutes, ResourceTitles } from "lvp/PropsTypes";
import LineChart from "lvp/Components/Widgets/Chats/LineChart.vue";
import BaseChart from "lvp/Components/Widgets/Chats/BaseChart.vue";
import FormWidget from "lvp/Components/Widgets/FormWidget.vue";
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
  resources_routes: ResourceRoutes;
  data: Object | any;
  table_filters: any;
  widgets: Widget[];
  before_content_widgets: any[];
  after_content_widgets: any[];
  form_fields: any;
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
//------------------Widgets-----------
</script>
