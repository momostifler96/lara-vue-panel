<template>
    <button
        type="button"
        @click="showMenu"
        class="w-8 h-8 hover:bg-[#F5F5F5] transition-colors text-black rounded flex-center focus:ring-0 outline-0 p-1"
        v-html="icons['menu']"
    ></button>
    <Menu
        ref="tableActionMenu"
        :model="{
            items: actions,
        }"
        :popup="true"
        class=""
    >
        <template #item="{ item, props }">
            <ActionMenuButton
                v-for="action in item"
                class="w-full"
                :icon="action_icons[action.icon]"
                :label="action.label"
                :action="action.action"
                :color="action.color"
                @click="$emit('exec', action.action)"
            />
        </template>
    </Menu>
</template>

<script setup lang="ts">
import { ref } from "vue";
import Menu from "primevue/menu";
import ActionMenuButton from "lvp/Components/Widgets/ActionMenuButton.vue";
import icons, {
    DeleteIcon,
    DownloadIcon,
    EditIcon,
    LinkIcon,
    MoveIcon,
    ViewIcon,
} from "lvp/helpers/lvp_icons";

const action_icons = <Record<string, any>>{
    edit: EditIcon,
    view: ViewIcon,
    delete: DeleteIcon,
    export: DownloadIcon,
    download: DownloadIcon,
    move: MoveIcon,
    link: LinkIcon,
};
import {
    TrashIcon,
    PencilIcon,
    EyeIcon,
    ArrowUpTrayIcon,
    ArrowDownTrayIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    actions: Array,
});

const emit = defineEmits(["exec"]);
const tableActionMenu = ref();
const showMenu = (event: any) => {
    tableActionMenu.value.toggle(event);
};
</script>
