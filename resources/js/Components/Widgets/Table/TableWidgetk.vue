<template>
  <div class="lvp-table-wrapper">
    <div class="p-3" v-if="$slots.t_header">
      <slot name="t_header" />
    </div>
    <div v-if="$slots.t_leading || $slots.t_action" class="flex justify-between px-2 pt-2 my-2">
      <span class="flex items-center gap-3">
        <slot name="t_leading" />
      </span>
      <span class="flex items-center gap-3">
        <slot name="t_action" />
      </span>
    </div>
    <div class="relative overflow-x-auto">
      <table class="lvp-table" :class="{
        'fixe-last-columns': fixeLastColumn,
        'fixe-first-columns': fixeFirstColumn,
      }">
        <thead class="lvp-table-header">
          <tr>
            <th class="px-2 py-3 text-nowrap lvp-table-header-first-column">
              <input type="checkbox" class="lvp-checkbox" :checked="props.data?.length == selected?.length"
                @change="selectAll" />
            </th>
            <template v-for="(column, c_i) in props.columns" :key="column.field">
              <th scope="col" :align="column.align" class="px-2 py-3 text-nowrap " :class="[
                {
                  'lvp-table-header-last-column':
                    c_i === props.columns.length - 1,
                },
              ]" v-if="actives_cols.includes(column.field)">
                {{ column.label }}
              </th>
            </template>

          </tr>
        </thead>
        <tbody>
          <tr v-for="item in props.data" :key="item.id" class="lvp-table-row">
            <th class="px-2 py-3 text-nowrap lvp-table-body-first-column">
              <input type="checkbox" class="lvp-checkbox" :checked="selected?.includes(item.id)" @change="select"
                :value="item.id" />
            </th>
            <template v-for="(column, c_i) in props.columns" :key="column.field">
              <td :align="column.align" scope="row" class="lvp-table-row-data" :class="[
                {
                  'lvp-table-body-last-column':
                    c_i === props.columns.length - 1,
                },
              ]" v-if="actives_cols.includes(column.field)">
                <div class="flex" :class="{
                  'justify-end': column.align === 'right',
                  'justify-start': column.align === 'left',
                  'justify-center': column.align === 'center',
                }" v-if="actives_cols.includes(column.field)">
                  <slot :name="column.field" :item="item" :column="column">
                    <TableColumnEngine :column="column" :item="item" @dataEvent="emitTableData($event, item.id)" />
                  </slot>
                </div>
              </td>
            </template>

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
import { computed, inject, ref, watch } from "vue";
import ImageColumn from "./Columns/ImageColumn.vue";
import TextColumn from "./Columns/TextColumn.vue";
import BadgeColumn from "./Columns/BadgeColumn.vue";
import DropdownColumn from "./Columns/DropdownColumn.vue";
import ToggleColumn from "./Columns/ToggleColumn.vue";
import GroupColumn from "./Columns/GroupColumn.vue";
import TableColumnEngine from "../TableColumnEngine.vue";
const props = defineProps({
  data: {
    type: Array<any>,
    required: true,
  },
  hasHeader: Boolean,
  hasFooter: Boolean,
  fixeLastColumn: Boolean,
  fixeFirstColumn: Boolean,
  columns: {
    type: Array as () => TableColumn[],
    required: true,
  },
  selected: {
    type: Array,
    default: [],
  }, activesCols: {
    type: Array,
  },
});
const plugin_columns = <{ [k: string]: any }>(
  inject("lvp_datatable_columns")
);

const columns_components = {
  image: ImageColumn,
  text: TextColumn,
  badge: BadgeColumn,
  dropdown: DropdownColumn,
  toggle: ToggleColumn,
  group: GroupColumn,
  ...plugin_columns
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
const actives_cols = computed(() => props.activesCols ?? props.columns.map((it: any) => it.field));

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
