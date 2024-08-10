<template>
  <div class="space-y-5">
    <component
      v-for="info in props.infos"
      :is="infos_list[info.type]"
      :key="info.type"
      v-bind="info.props"
      :data="props.data"
    />
  </div>
</template>
<script setup lang="ts">
import Section from "./Section.vue";
import BadgeInfo from "./BadgeInfo.vue";
import ImageInfo from "./ImageInfo.vue";
import TextInfo from "./TextInfo.vue";
import GridInfo from "./GridInfo.vue";
import BaseChart from "lvp/Components/Widgets/Chats/BaseChart.vue";
import FormWidget from "lvp/Components/Widgets/FormWidget.vue";
import DataTableWidget from "lvp/Components/Widgets/Table/DataTableWidget.vue";
const props = defineProps({
  infos: {
    type: Array as () => Array<any>,
    required: true,
  },
  data: {
    type: Object,
    required: true,
  },
});

const infos_list = <{ [k: string]: any }>{
  section: Section,
  text: TextInfo,
  image: ImageInfo,
  badge: BadgeInfo,
  grid: GridInfo,
  "data-table": DataTableWidget,
  chart: BaseChart,
  form: FormWidget,
};

console.log("Infos lites", props.infos);
</script>
