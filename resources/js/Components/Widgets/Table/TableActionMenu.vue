<template>
  <button
    type="button"
    @click="showMenu"
    class="w-8 h-8 hover:bg-[#F5F5F5] transition-colors text-black rounded flex-center focus:ring-0 outline-0 p-1"
    v-html="icons['menu_2']"
  ></button>
  <Menu
    ref="tableActionMenu"
    :model="{
      items: tableActions,
    }"
    :popup="true"
    class=""
  >
    <template #item="{ item, props }">
      <TableActionButton
        v-for="action in item"
        class="w-full"
        :icon="action_icons[action.icon]"
        :label="action.label"
        :action="action.action"
        :color="action.color"
        :item="tableItem"
        @click="$emit('exec', action.action, tableItem)"
      />
    </template>
  </Menu>
</template>

<script setup lang="ts">
import { inject, ref } from "vue";
import Menu from "primevue/menu";
import TableActionButton from "./TableActionButton.vue";
import icons, {
  DeleteIcon,
  DownloadIcon,
  EditIcon,
  ViewIcon,
} from "lvp/helpers/lvp_icons";

const props = defineProps({
  tableItem: Object,
  tableActions: Array,
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
  eye: ViewIcon,
  delete: DeleteIcon,
  export: DownloadIcon,
  download: DownloadIcon,
  ...lvp_plugin_icons,
};
</script>
