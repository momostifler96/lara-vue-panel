<template>
    <GroupColumn v-if="column.type == 'group'" :data="item" :field="column.field" :column="column" />
    <component v-else :is="columns_components[column.type]" :data="getItemData(item, column.field)"
        :field="column.field" :column="column" @dataEvent="(e: any) => { emitTableData(e, item.id); }" />
</template>
<script setup lang="ts">
import { inject } from 'vue';
import BadgeColumn from './Table/Columns/BadgeColumn.vue';
import DropdownColumn from './Table/Columns/DropdownColumn.vue';
import GroupColumn from './Table/Columns/GroupColumn.vue';
import ImageColumn from './Table/Columns/ImageColumn.vue';
import TextColumn from './Table/Columns/TextColumn.vue';
import ToggleColumn from './Table/Columns/ToggleColumn.vue';
import LinkColumn from './Table/Columns/LinkColumn.vue';

const proops = defineProps({
    column: {
        type: Object as () => any,
        required: true,
    },
    item: {
        type: Object,
        required: true,
    },
});

const plugin_columns = <{ [k: string]: any }>(
    inject("lvp_datatable_columns")
);

const columns_components = <{ [k: string]: any }>{
    image: ImageColumn,
    text: TextColumn,
    badge: BadgeColumn,
    dropdown: DropdownColumn,
    toggle: ToggleColumn,
    group: GroupColumn,
    link: LinkColumn,
    ...plugin_columns
};
const emit = defineEmits(["update:selected", "dataEvent"]);

const emitTableData = (data: any, id: string) => {
    emit("dataEvent", { ...data, item_id: id });
};

const getItemData = (item: any, field: string) => {
    if (
        field.indexOf("related.") > -1 ||
        field.indexOf("count.") > -1 ||
        field.indexOf(".") == -1
    ) {
        return item[field];
    } else if (field.indexOf(".") > -1) {
        const keys = field.split(".");
        if (keys[5]) {
            return item[keys[0]][keys[1]][keys[2]][keys[3]][keys[4]][keys[5]];
        } else if (keys[4]) {
            return item[keys[0]][keys[1]][keys[2]][keys[3]][keys[4]];
        } else if (keys[3]) {
            return item[keys[0]][keys[1]][keys[2]][keys[3]];
        } else if (keys[2]) {
            return item[keys[0]][keys[1]][keys[2]];
        } else if (keys[1]) {
            return item[keys[0]][keys[1]];
        } else if (keys[0]) {
            return item[keys[0]];
        }
    }
};
</script>