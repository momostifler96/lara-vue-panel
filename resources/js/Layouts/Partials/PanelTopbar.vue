<template>
  <header class="lvp-panel-top-bar">
    <nav class="relative flex justify-between w-full my-auto">
      <span class="flex justify-start">
        <span class="lvp-panel-top-bar-drawer-btn" @click="showDrawer">
          <span v-html="ListIcon" class="w-6 h-6" />
        </span>
        <TextField @update:model-value="onSearch" placeholder="Recherche" class="w-80">
          <template #prefix>
            <span class="surfix-icon" v-html="SearchIcon"></span>
          </template>
        </TextField>
      </span>
      <span class="flex gap-3">
        <span class="lvp-panel-top-bar-icon" @click="openNofications">
          <span class="lvp-panel-top-bar-icon-badge">{{ $page.props.notifications }}
          </span>
          <NotificationIcon />
        </span>
        <span>
          <UserMenu v-if="$page.props.auth" />
        </span>
      </span>
    </nav>
  </header>
</template>
<script setup lang="ts">
import NotificationIcon from "lvp/Components/Icons/Notification.vue";
import UserMenu from "./DropdownMenu/UserMenu.vue";
import { ref } from "vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import icons from "lvp/Assets/Icons";
import { SearchIcon, ListIcon } from "lvp/svg_icons";
const openNofications = () => {
  const event = new CustomEvent("open-notification");
  document.dispatchEvent(event);
};

const showDrawer = () => {
  const event = new CustomEvent("show-drawer");
  document.dispatchEvent(event);
};

const onSearch = (val: string) => {
  console.log("example", val);
};
</script>
