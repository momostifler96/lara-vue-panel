<template>
  <div
    class="flex"
    :style="`gap:${column.gap}px; align-items:${column.align_items};`"
  >
    <span v-for="group in column.groups" class="flex flex-col">
      <component
        v-for="item in group"
        :is="columns_components[item.type]"
        :data="getItemData(data, item.field)"
        :field="item.field"
        :column="item"
        @dataEvent="(e:any)=>{emitTableData(e,item.id)}"
      />
    </span>
  </div>
</template>
<script setup lang="ts">
import TextColumn from "lvp/Components/Widgets/Table/Columns/TextColumn.vue";
import ImageColumn from "lvp/Components/Widgets/Table/Columns/ImageColumn.vue";
import DropdownColumn from "lvp/Components/Widgets/Table/Columns/DropdownColumn.vue";
import ToggleColumn from "lvp/Components/Widgets/Table/Columns/ToggleColumn.vue";
import BadgeColumn from "./BadgeColumn.vue";

defineProps<{
  field: string;
  data: any;
  column: any;
}>();
const emit = defineEmits(["dataEvent"]);

const columns_components = <{ [k: string]: any }>{
  text: TextColumn,
  image: ImageColumn,
  dropdown: DropdownColumn,
  badge: BadgeColumn,
  toggle: ToggleColumn,
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
    let _item = item;
    keys.forEach((it, i) => {
      console.log("it", it, _item["info"]);
      _item = _item[it];
    });
    return _item;
    // console.log("getItemData", _item);
    // if (keys[5]) {
    //   return item[keys[0]][keys[1]][keys[2]][keys[3]][keys[4]][keys[5]];
    // } else if (keys[4]) {
    //   return item[keys[0]][keys[1]][keys[2]][keys[3]][keys[4]];
    // } else if (keys[3]) {
    //   return item[keys[0]][keys[1]][keys[2]][keys[3]];
    // } else if (keys[2]) {
    //   return item[keys[0]][keys[1]][keys[2]];
    // } else if (keys[1]) {
    //   return item[keys[0]][keys[1]];
    // } else if (keys[0]) {
    //   return item[keys[0]];
    // }
  }
};
const emitTableData = (data: any, id: string) => {
  emit("dataEvent", { ...data, item_id: id });
};
</script>
