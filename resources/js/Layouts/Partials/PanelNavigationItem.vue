<template>
  <Link
    :href="data.path"
    class="lvp-panel-nav-link"
    :class="{ active: $page.url === data.path }"
  >
    <span class="w-6 h-6" v-html="icons[data.icon]"> </span>
    <span>{{ data.label }}</span>
  </Link>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import lvp_icons from "lvp/helpers/lvp_icons";
import { NavLink } from "lvp/types/index";

const props = defineProps<{
  data: NavLink;
}>();
const icons = lvp_icons as any;
</script>
