<template>
  <button type="button" @click="showMenu" :class="btnClass">
    <span v-html="icon" class="w-5 h-5"></span>
    <span v-show="label && label != ''" class="ml-2">{{ label }}</span>
  </button>
  <Menu ref="tableActionMenu" :model="{
    items: actions,
  }" :popup="true" class="">
    <template #item="{ item, props }">
      <TableActionButton v-for="action in item" class="w-full" :icon="action_icons[action.icon]" :label="action.label"
        :action="action.action" :color="action.color" :item="null" @click="$emit('exec', action.action)" />
    </template>
  </Menu>
</template>

<script setup lang="ts">
import { inject, ref } from "vue";
import Menu from "primevue/menu";
import TableActionButton from "./LVPActionMenuButon.vue";
import type { LVPActionMenuOption } from "lvp/helpers/types";

import {
  DeleteIcon,
  DownloadIcon,
  EditIcon,
  MoveIcon,
  ViewIcon,
} from "lvp/svg_icons";

const props = defineProps({
  actions: {
    type: Object as () => LVPActionMenuOption,
    required: true,
  }, label: {
    type: String,
    required: true,
  }, icon: {
    type: String,
    required: true,
  },
  btnClass: {
    type: String,
    default: 'lvp-button-lite'
  }
});

const emit = defineEmits(["exec"]);
const tableActionMenu = ref();
const showMenu = (event: any) => {
  tableActionMenu.value.toggle(event);
};
const lvp_plugin_icons = <Record<string, any>>inject("lvp_icons");

const action_icons = <Record<string, any>>{
  edit: EditIcon,
  view: ViewIcon,
  delete: DeleteIcon,
  export: DownloadIcon,
  download: DownloadIcon,
  move: MoveIcon,
  ...lvp_plugin_icons,
};
</script>
