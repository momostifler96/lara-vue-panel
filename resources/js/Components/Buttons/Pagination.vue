<template>
  <vue-awesome-paginate
    :total-items="totalItems"
    :items-per-page="itemsPerPage"
    :max-pages-shown="5"
    :modelValue="modelValue"
    @update:modelValue="$emit('update:modelValue', $event)"
    :show-ending-buttons="true"
    :show-breakpoint-buttons="false"
    @click="$emit('paginate', $event)"
  >
    <template #prev-button>
      <span v-html="ChevronLeftIcon" class="w-4 h-4"></span>
    </template>
    <template #next-button>
      <span v-html="ChevronRightIcon" class="w-4 h-4"></span>
    </template>
    <template #first-page-button>
      <span v-html="ChevronDoubleLeftIcon" class="w-4 h-4"></span>
    </template>
    <template #last-page-button>
      <span v-html="ChevronDoubleRightIcon" class="w-4 h-4"></span>
    </template>
  </vue-awesome-paginate>
</template>
<script setup lang="ts">
import { ref } from "vue";
import {
  ChevronLeftIcon,
  ChevronRightIcon,
  ChevronDoubleRightIcon,
  ChevronDoubleLeftIcon,
} from "lvp/helpers/lvp_icons";
const props = defineProps({
  totalItems: {
    type: Number,
    default: 0,
  },
  itemsPerPage: {
    type: Number,
    default: 0,
  },
  modelValue: {
    type: Number,
    default: 0,
  },
});

const emit = defineEmits(["paginate", "update:modelValue"]);

const onClickHandler = (page: number) => {
  console.log(page);
};
</script>
<style lang="scss">
.pagination-container {
  display: flex;

  column-gap: 10px;
}

.paginate-buttons {
  height: 30px;
  min-width: 30px;
  padding: 0px 5px;
  border-radius: var(--lvp-border-radius);
  cursor: pointer;
  transition: background-color;
  transition-duration: var(--lvp-transition-duration);
  transition-timing-function: var(--lvp-transition-timing-function);
  background-color: rgb(248, 248, 248);
  border: 1px solid rgb(234, 234, 234);
  color: black;
}

.paginate-buttons:hover {
  background-color: #ebebeb;
}

.active-page {
  background-color: var(--lvp-color-primary);

  border: 1px solid var(--lvp-color-primary);

  color: white;
}

.active-page:hover {
  background-color: var(--lvp-color-primary-hover);
}
.paginate-buttons {
  &.back-button,
  &.next-button {
  }
}
</style>
