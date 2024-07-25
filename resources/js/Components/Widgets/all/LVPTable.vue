<template>
    <div class="lvp-table-wrapper">
        <div class="p-3" v-if="hasHeader">
            <slot name="header" />
        </div>
        <div class="flex justify-between px-2 pt-2 my-2">
            <span class="flex items-center gap-3">
                <slot name="filter" />
            </span>
            <span>
                <slot name="search" />
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
                        <th
                            class="px-2 py-3 text-nowrap vp-table-header-first-column"
                        >
                            <input
                                type="checkbox"
                                class="lvp-checkbox"
                                :checked="
                                    props.data?.length == selected?.length
                                "
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
                    <tr
                        v-for="item in props.data"
                        :key="item.id"
                        class="lvp-table-row"
                    >
                        <th
                            class="px-2 py-3 text-nowrap vp-table-body-first-column"
                        >
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
                                <slot
                                    :name="column.field"
                                    :item="item"
                                    :column="column"
                                >
                                    <template v-if="column.type === 'link'">
                                        <a href="#">{{ item[column.field] }}</a>
                                    </template>
                                    <template
                                        v-else-if="column.type === 'image'"
                                    >
                                        <img
                                            :src="
                                                (column.file_path ?? '') +
                                                item[column.field]
                                            "
                                            class="max-h-14 min-h-10 max-w-14 min-w-10"
                                        />
                                    </template>
                                    <template
                                        v-else-if="column.type === 'avatar'"
                                    >
                                        <img
                                            :src="
                                                (column.file_path ?? '') +
                                                item[column.field]
                                            "
                                            class="flex w-8 h-8"
                                        />
                                    </template>

                                    <template v-else>
                                        {{ item[column.field] }}
                                    </template>
                                </slot>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-3" v-if="hasFooter">
            <slot name="footer" />
        </div>
    </div>
</template>
<script setup lang="ts">
import type { TableColumn } from "lvp/helpers/types";
import { watch } from "vue";
const props = defineProps({
    data: {
        type: Array<any>,
        required: true,
    },
    hasHeader: Boolean,
    hasFooter: Boolean,
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
const emit = defineEmits(["update:selected"]);
const selectAll = (event: any) => {
    emit(
        "update:selected",
        event.target.checked ? props.data.map((item) => item.id) : []
    );
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
</script>
