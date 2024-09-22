<template>
  <Link :href="data.path" class="lvp-panel-nav-link" :class="{ active: $page.url === data.path }">
  <span class="w-5 h-5" v-html="icons[data.icon]"> </span>
  <span>{{ data.label }}</span>
  </Link>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import lvp_icons from "lvp/helpers/lvp_icons";
import { NavLink } from "lvp/types/index";
import { inject } from "vue";

const props = defineProps<{
  data: NavLink;
}>();
const plugin_icons = <{ [k: string]: any }>inject("lvp_icons");
const icons = <{ [k: string]: any }>{ ...lvp_icons, ...plugin_icons };
</script>
