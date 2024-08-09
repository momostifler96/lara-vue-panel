<template>
  <div :class="`grid grid-cols-${props.gridCols} gap-${props.gap}`">
    <component
      v-for="info in props.infos"
      :is="infos_list[info.type]"
      :key="info.label"
      v-bind="info.props"
      :data="props.data"
    />
  </div>
</template>
<script setup lang="ts">
import BadgeInfo from "./BadgeInfo.vue";
import ImageInfo from "./ImageInfo.vue";
import Section from "./Section.vue";
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
  section: Section,
  // grid: GridInfo,
  text: TextInfo,
  image: ImageInfo,
  badge: BadgeInfo,
};
</script>
