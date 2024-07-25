<template>
    <div v-if="actions?.type == 'inline'" class="flex gap-2">
        <button
            v-for="(action, i) in actions?.actions"
            class="lvp-button-lite"
            :class="action.color"
        >
            {{ action.label }}
        </button>
    </div>
    <template v-else-if="actions?.type == 'dropdown'">
        <button type="button" @click="showMenu" class="lvp-button-lite">
            <span v-html="Menu2Icon" class="w-5 h-5"></span>
            <span>Bulk action</span>
        </button>
        <Menu
            ref="tableActionMenu"
            :model="{
                items: actions.actions,
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
                    :item="null"
                    @click="$emit('exec', action.action)"
                />
            </template>
        </Menu>
    </template>
</template>

<script setup lang="ts">
import { inject, ref } from "vue";
import Menu from "primevue/menu";
import TableActionButton from "./TableActionButton.vue";
import type { ActionMenu } from "lvp/helpers/types";
import { Menu2Icon } from "lvp/helpers/lvp_icons";

import icons, {
    DeleteIcon,
    DownloadIcon,
    EditIcon,
    MoveIcon,
    ViewIcon,
} from "lvp/helpers/lvp_icons";

const props = defineProps({
    actions: Object as () => ActionMenu,
});

const tableActions = [
    {
        label: "Export exel",
        action: "export-exel",
        icon: "download",
        color: "",
    },
    {
        label: "Export csv",
        action: "export-csv",
        icon: "download",
        color: "",
    },
    {
        label: "Delete all",
        action: "delete",
        icon: "delete",
        color: "text-lvp-danger",
    },
];

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
