<template>
  <LVPModal :show="show" @update:close="cancel" size="xs">
    <div class="flex-center">
      <span class="flex h-12 p-3 bg-lite rounded-xl w-[50px] text-gray-50" v-html="icons[icon]" :class="colors[icon]">
      </span>
    </div>
    <div class="my-4 mb-10">
      <h4 class="mb-3 text-center">{{ title }}</h4>
      <p class="text-sm text-center text-gray-500">{{ body }}</p>
      <TextField v-if="hasPassword" id="password" class="mt-5" label="Password" v-model="password" />
    </div>
    <div class="w-full h-full gap-3 flex-center p-1">
      <button @click="cancel" class="w-full lvp-button cancel">
        {{ cancelLabel }}
      </button>
      <button @click="confirm" class="w-full lvp-button confirm">
        {{ confirmLabel }}
      </button>
    </div>
  </LVPModal>
</template>
<script setup lang="ts">
import {
  SuccessIcon,
  WarningIcon,
  InfoIcon,
  DeleteIcon,
} from "./../../Assets/Icons";
import TextField from "../Forms/TextField.vue";
import { ref } from "vue";
import LVPModal from "./LVPModal.vue";

const props = defineProps({
  show: Boolean,
  hasPassword: Boolean,
  icon: {
    type: String as () => "info" | "warning" | "delete" | "success",
    default: "info",
  },
  title: {
    type: String,
    default: "Confirmation",
  },
  body: {
    type: String,
    default: "Are you sure you want to proceed?",
  },
  cancelLabel: {
    type: String,
    default: "Cancel",
  },
  confirmLabel: {
    type: String,
    default: "Confirm",
  },
});

const emit = defineEmits(["update:show", "onResponse", "onClose"]);

const confirm = () => {
  emit("onResponse", true, password.value);
};

const cancel = () => {
  emit("onResponse", false);
};
const password = ref("");
const icons = {
  info: InfoIcon,
  warning: WarningIcon,
  delete: DeleteIcon,
  success: SuccessIcon,
};

const colors = {
  info: "lvp-text-info lvp-bg-info",
  warning: "lvp-text-warn lvp-bg-warn",
  delete: "lvp-text-danger lvp-bg-danger",
  success: "lvp-text-success lvp-bg-success",
};
</script>
