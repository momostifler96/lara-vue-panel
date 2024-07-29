<template>
  <HeadlessModal :show="show" @update:close="cancel">
    <div class="flex-center">
      <span
        class="flex h-12 p-3 bg-lite rounded-xl w-[50px] text-gray-50"
        v-html="icons[icon]"
        :class="colors[icon]"
      >
      </span>
    </div>
    <div class="my-4 mb-10">
      <h4 class="mb-3 text-center">{{ title }}</h4>
      <p class="text-sm text-center text-gray-500">{{ body }}</p>
    </div>
    <div class="w-full h-full gap-3 flex-center">
      <button @click="cancel" class="w-full lvp-button cancel">
        {{ cancelLabel }}
      </button>
      <button @click="confirm" class="w-full lvp-button confirm">
        {{ confirmLabel }}
      </button>
    </div>
  </HeadlessModal>
</template>
<script setup lang="ts">
import { defineProps, defineEmits } from "vue";
import HeadlessModal from "./HeadlessModal.vue";
import {
  SuccessIcon,
  WarningIcon,
  InfoIcon,
  DeleteIcon,
} from "./../../Assets/Icons";

const props = defineProps({
  show: Boolean,
  icon: {
    type: String,
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

const emit = defineEmits(["update:show", "onResponse"]);

const confirm = () => {
  emit("onResponse", true);
};

const cancel = () => {
  emit("onResponse", false);
};

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
