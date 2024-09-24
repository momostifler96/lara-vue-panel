<template>
  <PanelLayout :pageTitle="props.page_titles.info">
    <template #actions>
      <slot name="actions" />
    </template>

    <InfoBlock :infos="props.widgets" :data="props.model_infos" />

    <div class="flex justify-between mt-10">
      <SimpleButton buttonType="link" :href="route(props.routes.index)">Retour</SimpleButton>
      <div class="flex gap-3">
        <SimpleButton>Modifier</SimpleButton>
        <SimpleButton color="danger" @click="item_actions.delete">Supprimer</SimpleButton>
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
  <ConfirmationModal :show="confirmation_modal.show" icon="delete" :title="confirmation_modal.title"
    :body="confirmation_modal.body" :hasPassword="confirmation_modal.has_password"
    :cancelLabel="confirmation_modal.cancel_button_label" :confirmLabel="confirmation_modal.confirm_button_label"
    @onResponse="confirmation_modal.onResponse" />
</template>
<script setup lang="ts">
import PanelLayout from "../Partials/PanelLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
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
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";
import { ActionsList } from "lvp/helpers/types";
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
const confirmation_modal = reactive({
  show: false,
  title: "",
  body: "",
  has_password: false,
  cancel_button_label: "Annuler",
  confirm_button_label: "Confirmer",
  onResponse: (rsp: boolean, password: string) => { },
});

const item_actions = <ActionsList>{
  edit: ({ route_list, item }) => {
    // emit("edit", item);
  },

  delete: (opt) => {
    confirmation_modal.title = "Delete";
    confirmation_modal.body = "Are you sure you want to delete this item?";
    confirmation_modal.cancel_button_label = "Annuler";
    confirmation_modal.confirm_button_label = "Confirmer";
    confirmation_modal.has_password = false;
    confirmation_modal.onResponse = (rsp: boolean, password: string) => {
      if (rsp) {
        router.delete(
          route(props.value.routes.delete, {
            id: props.value.model_infos.id,
            redirect_to: props.value.routes.index,
          })
        );
      } else {
      }
      confirmation_modal.show = false;
      confirmation_modal.title = "";
      confirmation_modal.body = "";
    };
    confirmation_modal.show = true;
  },
};
</script>
