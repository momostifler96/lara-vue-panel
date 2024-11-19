<template>
  <PanelLayout :pageTitle="props.page_titles.title">
    <template #actions>
      <slot name="actions" />
      <SimpleButton @click="creatResource">{{
        props.labels.create
      }}</SimpleButton>
    </template>
    <WidgetEngine :widgets="props.before_data_widgets" />
    <!-- <div v-if="props.before_data_widgets.length > 0" class="grid grid-cols-3 gap-3 mt-10 mb-10 tt">
      <component v-for="(widget, i) in props.before_data_widgets" :is="widgets_components[widget.widget_type]"
        v-bind="widget" :key="`widget-${widget.widget_type}-${i}`" :class="`col-span-${widget.col_span}`" />
    </div> -->
    <div class="">
      <DataComponent v-bind="props.data_widget" key="data-widget" :routes="props.routes"
        @edit-resource="editResource" />
    </div>

    <div v-if="props.after_data_widgets.length > 0" class="grid grid-cols-3 gap-3 mt-10 mb-10">
      <component v-for="(widget, i) in props.after_data_widgets" :is="widgets_components[widget.widget_type]"
        v-bind="widget" :key="`widget-${widget.widget_type}-${i}`" :class="`col-span-${widget.col_span}`" />
    </div>
  </PanelLayout>
  <ModalForm @close="form_modal.show = false" :show="form_modal.show" :formData="modal_form_data"
    :action="form_modal.action" v-bind="props.modal_form" :routes="props.routes" />

</template>
<script setup lang="ts">
import PanelLayout from "../Partials/PanelLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";
import DataTableWidget from "lvp/Components/Widgets/Table/DataTableWidget.vue";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import DataComponent from "./DataComponent.vue";

import { ResourceRoutes } from "lvp/PropsTypes";
import LineChart from "lvp/Components/Widgets/Chats/LineChart.vue";
import BaseChart from "lvp/Components/Widgets/Chats/BaseChart.vue";
import FormWidget from "lvp/Components/Widgets/FormWidget.vue";
import ModalForm from "./ModalForm.vue";
import WidgetEngine from "../WidgetEngine.vue";
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

  modal_form: {
    form_widget: {
      label: string;
      props: {
        formData: { [k: string]: any };
      };
      type: 'modal' | 'page';

    };
    routes: ResourceRoutes;
    titles: {
      create: string;
      edit: string;
    };
  };
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
});
const modal_form_data = ref<any>()
const creatResource = () => {
  if (props.value.form_type === "modal") {
    const _form_data = props.value.modal_form.form_widget.props.formData;
    modal_form_data.value = null;
    console.log('_form_data', _form_data);
    modal_form_data.value = _form_data;
    form_modal.action = "create";
    form_modal.show = true;
  } else {
    router.get(route(props.value.routes.create));
  }

};
const editResource = (item: any) => {
  if (props.value.form_type === "modal") {
    const formData = props.value.modal_form.form_widget.props.formData;
    modal_form_data.value = props.value.modal_form.form_widget.props.formData;
    const keys = Object.keys(formData);
    modal_form_data.value.id = item.id;
    keys.forEach((key) => {
      modal_form_data.value[key] = item[key];
    });
    form_modal.action = "edit";
    form_modal.show = true;

  } else {
    router.get(route(props.value.routes.edit, { id: item.id }));
  }
};


//------------------Widgets-----------
</script>
