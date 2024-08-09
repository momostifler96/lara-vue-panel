<template>
  <PanelLayout :pageTitle="props.page_titles.info">
    <template #actions>
      <slot name="actions" />
    </template>

    <div
      v-if="props.before_data_widgets.length > 0"
      class="grid grid-cols-3 gap-3 mt-10 mb-10 tt"
    >
      <!-- <component
        v-for="(widget, i) in props.before_data_widgets"
        :is="widgets_components[widget.widget_type]"
        v-bind="widget"
        :key="`widget-${widget.widget_type}-${i}`"
        :class="`col-span-${widget.col_span}`"
      /> -->
    </div>
    <div class=""></div>
    <InfoBlock :infos="props.info_widgets" :data="props.model_infos" />
    <div
      v-if="props.after_data_widgets.length > 0"
      class="grid grid-cols-3 gap-3 mt-10 mb-10"
    >
      <!-- <component
        v-for="(widget, i) in props.after_data_widgets"
        :is="widgets_components[widget.widget_type]"
        v-bind="widget"
        :key="`widget-${widget.widget_type}-${i}`"
        :class="`col-span-${widget.col_span}`"
      /> -->
    </div>
    <div class="flex justify-between mt-10">
      <SimpleButton buttonType="link" :href="route(props.routes.index)"
        >Retour</SimpleButton
      >
      <div class="flex gap-3">
        <SimpleButton>Modifier</SimpleButton>
        <SimpleButton color="danger">Supprimer</SimpleButton>
      </div>
    </div>
  </PanelLayout>
  <!-- <ModalForm
    @close="form_modal.show = false"
    :show="form_modal.show"
    :defaultData="form_modal.data"
    :action="form_modal.action"
    v-bind="props.modal_form"
  /> -->
</template>
<script setup lang="ts">
import PanelLayout from "../Partials/PanelLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { computed, reactive } from "vue";
import DataTableWidget from "lvp/Components/Widgets/Table/DataTableWidget.vue";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import DataComponent from "./DataComponent.vue";

import { ResourceRoutes } from "lvp/PropsTypes";
import LineChart from "lvp/Components/Widgets/Chats/LineChart.vue";
import BaseChart from "lvp/Components/Widgets/Chats/BaseChart.vue";
import FormWidget from "lvp/Components/Widgets/FormWidget.vue";
import ModalForm from "./ModalForm.vue";
import InfoBlock from "./InfoBlocks/InfoBlock.vue";
interface Titles {
  title: string;
  meta_title: string;
  info: string;
  label: string;
  description_title: string;
}

interface Widget {
  type: string;
  data: { [k: string]: any };
}

interface ResourceIndexPage {
  page_titles: Titles;
  labels: any;
  routes: ResourceRoutes;
  model_infos: Object | any;
  before_data_widgets: any[];
  info_widgets: any[];
  after_data_widgets: any[];
}

const props = computed(() => {
  return usePage().props as unknown as ResourceIndexPage;
});
console.log("props model_infos", props.value.model_infos);
</script>
