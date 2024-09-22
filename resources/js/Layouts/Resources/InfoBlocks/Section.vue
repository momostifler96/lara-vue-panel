<template>
  <div class="lvp-card">
    <div class="lvp-card-header">
      <div class="">
        <h3>{{ props.label }}</h3>
      </div>
      <div class=""></div>
    </div>
    <div :class="`lvp-card-body grid grid-cols-${props.gridCols} gap-${props.gap}`">
      <component v-for="info in props.infos" :is="infos_list[info.type]" :key="info.label" v-bind="info.props"
        :data="props.data" />
    </div>
  </div>
</template>
<script setup lang="ts">
import Section from "lvp/Components/Widgets/Section.vue";
import BadgeInfo from "./BadgeInfo.vue";
import GridInfo from "./GridInfo.vue";
import ImageInfo from "./ImageInfo.vue";
import TextInfo from "./TextInfo.vue";

const props = defineProps({
  infos: {
    type: Object,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  gap: {
    type: String,
    required: true,
  },
  gridCols: {
    type: Number,
    required: true,
  },
  data: {
    type: Object,
    required: true,
  },
});

const infos_list = <{ [k: string]: any }>{
  text: TextInfo,
  grid: GridInfo,
  section: Section,
  image: ImageInfo,
  badge: BadgeInfo,
};
</script>
