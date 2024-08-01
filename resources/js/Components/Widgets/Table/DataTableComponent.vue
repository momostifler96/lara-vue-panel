<template>
  <div class="lvp-table-wrapper">
    <div class="p-3" v-if="hasHeader">
      <slot name="t_header" />
    </div>
    <div v-if="hasHeaderFilter" class="flex justify-between px-2 pt-2 my-2">
      <span class="flex items-center gap-3">
        <slot name="t_leading" />
      </span>
      <span>
        <slot name="t_action" />
      </span>
    </div>
    <div class="relative overflow-x-auto">
      <table
        class="lvp-table"
        :class="{
          'fixe-last-columns': fixeLastColumns,
          'fixe-first-columns': fixeFirstColumns,
        }"
      >
        <thead class="lvp-table-header">
          <tr>
            <th class="px-2 py-3 text-nowrap vp-table-header-first-column">
              <input
                type="checkbox"
                class="lvp-checkbox"
                :checked="props.data?.length == selected?.length"
                @change="selectAll"
              />
            </th>
            <th
              v-for="(column, c_i) in props.columns"
              :key="column.field"
              scope="col"
              :align="column.align"
              class="px-2 py-3 text-nowrap vp-table-header-first-column"
              :class="[
                {
                  'lvp-table-header-last-column':
                    c_i === props.columns.length - 1,
                },
              ]"
            >
              {{ column.label }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in props.data" :key="item.id" class="lvp-table-row">
            <th class="px-2 py-3 text-nowrap vp-table-body-first-column">
              <input
                type="checkbox"
                class="lvp-checkbox"
                :checked="selected?.includes(item.id)"
                @change="select"
                :value="item.id"
              />
            </th>
            <td
              v-for="(column, c_i) in props.columns"
              :key="column.field"
              :align="column.align"
              scope="row"
              class="lvp-table-row-data"
              :class="[
                {
                  'lvp-table-body-last-column':
                    c_i === props.columns.length - 1,
                },
              ]"
            >
              <div
                class="flex"
                :class="{
                  'justify-end': column.align === 'right',
                  'justify-start': column.align === 'left',
                  'justify-center': column.align === 'center',
                }"
              >
                <slot :name="column.field" :item="item" :column="column">
                  <GroupColumn
                    v-if="column.type == 'group'"
                    :data="item"
                    :field="column.field"
                    :column="column"
                  />
                  <component
                    v-else
                    :is="columns_components[column.type]"
                    :data="getItemData(item, column.field)"
                    :field="column.field"
                    :column="column"
                    @dataEvent="(e:any)=>{emitTableData(e,item.id)}"
                  />
                </slot>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="p-3" v-if="hasFooter">
      <slot name="t_footer" />
    </div>
  </div>
</template>
<script setup lang="ts">
import type { TableColumn } from "../../../Types";
import { watch } from "vue";
import ImageColumn from "./Columns/ImageColumn.vue";
import TextColumn from "./Columns/TextColumn.vue";
import BadgeColumn from "./Columns/BadgeColumn.vue";
import DropdownColumn from "./Columns/DropdownColumn.vue";
import ToggleColumn from "./Columns/ToggleColumn.vue";
import GroupColumn from "./Columns/GroupColumn.vue";
const props = defineProps({
  data: {
    type: Array<any>,
    required: true,
  },
  hasHeader: Boolean,
  hasFooter: Boolean,
  hasHeaderFilter: Boolean,
  fixeLastColumns: Boolean,
  fixeFirstColumns: Boolean,
  columns: {
    type: Array as () => TableColumn[],
    required: true,
  },
  selected: {
    type: Array,
    default: [],
  },
});

const columns_components = <{ [key: string]: any }>{
  image: ImageColumn,
  text: TextColumn,
  badge: BadgeColumn,
  dropdown: DropdownColumn,
  toggle: ToggleColumn,
  group: GroupColumn,
};
const emit = defineEmits(["update:selected", "dataEvent"]);
const selectAll = (event: any) => {
  emit(
    "update:selected",
    event.target.checked ? props.data.map((item) => item.id) : []
  );
};
const emitTableData = (data: any, id: string) => {
  emit("dataEvent", { ...data, item_id: id });
};
const select = (event: any) => {
  let data = [...props.selected];
  if (event.target.checked) {
    data.push(event.target.value);
  } else {
    data = data.filter((it: any) => it != event.target.value);
  }
  emit("update:selected", data);
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
