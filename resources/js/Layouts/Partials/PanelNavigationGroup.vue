<template>
  <li>
    <div
      class="flex flex-col lvp-panel-navigation-group gap-y-2"
      :class="{ dismisable: data.dismisable }"
    >
      <span class="flex px-1 py-2 group-label flex justify-between"
        ><span>{{ data.label }}</span
        ><button
          v-if="data.dismisable"
          @click="extend = !extend"
          class="outline-none"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-4 h-4"
            :class="{ 'rotate-180': extend }"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M4.5 15.75l7.5-7.5 7.5 7.5"
            />
          </svg></button
      ></span>
      <div class="content" v-show="extend">
        <ul class="flex flex-col gap-y-2">
          <PanelNavigationItem
            v-for="item in data.children"
            :key="item.label"
            :data="item"
          />
        </ul>
      </div>
    </div>
  </li>
</template>
<script setup lang="ts">
import { ref } from "vue";
import PanelNavigationItem from "./PanelNavigationItem.vue";
import { NavGroup } from "lvp/types/index";

const props = defineProps<{
  data: NavGroup;
}>();

const extend = ref(true);
</script>
