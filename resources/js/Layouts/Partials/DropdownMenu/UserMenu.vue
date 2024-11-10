<template>
  <Popover v-slot="{ open }" class="relative">
    <PopoverButton class="flex w-8 h-8 rounded-full focus:ring-0 outline-0">
      <img class="w-8 h-8 rounded-full"
        :src="`https://avatar.iran.liara.run/username?username=${$page.props.auth.user.name}`" alt="avatar" />
    </PopoverButton>
    <transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
      enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
      leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
      <PopoverPanel class="absolute right-0 z-10 px-4 mt-3 transform sm:px-0">
        <div class="lvp-popover-overlay w-[250px]">
          <div class="lvp-popover-overlay-content">
            <Link v-for="(menu, i) in $page.props.user_menu as any[]" :href="menu.path" class="lvp-popover-link">
            <span v-html="UserIcon" class="w-5 h-5 mr-2" aria-hidden="true" />
            {{ menu.label }}
            </Link>
            <button type="button" @click="askLogout" class="lvp-popover-link">
              <span v-html="LogoutIcon" class="w-5 h-5 mr-2" aria-hidden="true" />
              Logout
            </button>
          </div>
        </div>
      </PopoverPanel>
    </transition>
  </Popover>
  <Teleport to="body">
    <ConfirmationModal :show="show_confirmation" title="Logout" body="Are you sure to logout?" confirmLabel="Logout"
      @onResponse="canLogout" />
  </Teleport>
</template>

<script setup lang="ts">
import {
  Popover,
  PopoverButton,
  PopoverPanel,
  MenuItem,
} from "@headlessui/vue";
import { Link, router, usePage } from "@inertiajs/vue3";

import { ref } from "vue";
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";
import { UserIcon, LogoutIcon } from "lvp/svg_icons";
const page = usePage();

const show_confirmation = ref(false);
const askLogout = () => {
  show_confirmation.value = true;
};
const canLogout = ($rsp: boolean) => {
  if ($rsp) {
    //@ts-ignore
    // console.log(
    //     "route(page.props.panel_data.logout_route)",
    //     route(page.props.panel_data.logout_route)
    // );
    //@ts-ignore
    router.post(route(page.props.panel_data.logout_route));
  }
  show_confirmation.value = false;
};
</script>
