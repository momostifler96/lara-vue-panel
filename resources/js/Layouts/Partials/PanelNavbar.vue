<template>
    <aside class="!z-50 lvp-navbar">
        <div class="relative w-80">
            <span class="lvp-navbar-top">
                <img :src="$page.props.admin_logo as any" class="h-10" alt="admin logo" />
            </span>
            <div class="lvp-navbar-bottom">
                <ul class="z-50 flex flex-col gap-y-3">
                    <template v-for="(nav, i) in $page.props.nav_menu as any[]">
                        <PanelNavigationGroup v-if="nav?.children != undefined" :data="nav" />
                        <PanelNavigationItem v-else :data="nav" />
                    </template>
                </ul>
            </div>
        </div>
    </aside>
</template>
<script setup lang="ts">
import PanelNavigationGroup from "./PanelNavigationGroup.vue";
import PanelNavigationItem from "./PanelNavigationItem.vue";
import { onMounted } from "vue";

onMounted(() => {
    // window.addEventListener("resize", (e) => {
    //     console.log("window width", e.target.innerWidth);
    // });
});
</script>
