<template>
    <AlertToast />
    <NotificationsDrawer />
    <Head>
        <title>{{ pageMetaTitle ?? pageTitle }}</title>
    </Head>
    <div class="lvp-panel-layout">
        <PanelContent :page-title="pageTitle" :breadcrumbs="breadcrumbs">
            <template #actions>
                <slot name="actions" />
            </template>
            <slot name="header" />
            <slot />
        </PanelContent>
        <PanelNavbar />
    </div>
    <Drawer v-model:show="showDrawer" position="left">
        <div class="relative">
            <span class="z-10 flex items-center shadow lvp-panel-top-bar">
                <img
                    :src="$page.props.admin_logo"
                    class="h-10"
                    alt="admin logo"
                />
            </span>
            <div class="p-3 overflow-y-auto lvp-navbar-bottom">
                <ul class="flex flex-col gap-y-3">
                    <template v-for="(nav, i) in $page.props.nav_menu">
                        <PanelNavigationGroup v-if="nav.children" :data="nav" />
                        <PanelNavigationItem v-else :data="nav" />
                    </template>
                </ul>
            </div>
        </div>
    </Drawer>
</template>
<script setup lang="ts">
import PanelNavbar from "./PanelNavbar.vue";
import PanelContent from "./PanelContent.vue";
import NotificationsDrawer from "./DropdownMenu/NotificationsDrawer.vue";
import Drawer from "./Drawer.vue";
import AlertToast from "lvp/Components/Widgets/AlertToast.vue";
import { onMounted, ref } from "vue";
import PanelNavigationGroup from "./PanelNavigationGroup.vue";
import PanelNavigationItem from "./PanelNavigationItem.vue";
import { Head, usePage, router } from "@inertiajs/vue3";

const page = usePage();
interface Breadcrumb {
    label: string;
    link: string;
}

const props = defineProps({
    pageTitle: String,
    pageMetaTitle: String,
    breadcrumbs: Array as () => Breadcrumb[] | null,
});
const showNotifications = ref(true);
const showDrawer = ref(false);

onMounted(() => {
    document.addEventListener("show-drawer", (e) => {
        showDrawer.value = true;
    });
});
</script>
