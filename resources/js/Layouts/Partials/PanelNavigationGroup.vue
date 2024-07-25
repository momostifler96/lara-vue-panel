<template>
    <li>
        <div
            class="flex flex-col mb-6 lvp-panel-navigation-group gap-y-2"
            :class="{ dismisable: data.dismisable }"
        >
            <span class="flex px-1 py-2">{{ data.label }}</span>
            <div class="content">
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
import PanelNavigationItem from "./PanelNavigationItem.vue";
import { NavGroup } from "lvp/types/index";

const props = defineProps<{
    data: NavGroup;
}>();
</script>
