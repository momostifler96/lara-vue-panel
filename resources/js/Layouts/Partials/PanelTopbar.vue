<template>
    <header class="lvp-panel-top-bar">
        <nav class="relative flex justify-between w-full my-auto">
            <span class="flex justify-start">
                <span class="lvp-panel-top-bar-drawer-btn" @click="showDrawer">
                    <MenuIcon class="w-6 h-6" />
                </span>
            </span>
            <span class="flex gap-3">
                <span class="lvp-panel-top-bar-icon" @click="openNofications">
                    <span class="lvp-panel-top-bar-icon-badge"
                        >{{ $page.props.notifications }}
                    </span>
                    <NotificationIcon />
                </span>
                <span>
                    <UserMenu />
                </span>
            </span>
        </nav>
    </header>
</template>
<script setup lang="ts">
import NotificationIcon from "lvp/Components/Icons/Notification.vue";
import { Bars3Icon as MenuIcon } from "@heroicons/vue/24/outline";
import UserMenu from "./DropdownMenu/UserMenu.vue";
import { ref } from "vue";
const openNofications = () => {
    const event = new CustomEvent("open-notification");
    document.dispatchEvent(event);
};

const showDrawer = () => {
    const event = new CustomEvent("show-drawer");
    document.dispatchEvent(event);
};
</script>
