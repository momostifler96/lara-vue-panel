<template>

  <LVPModal :show="show" :title="modalTitle" @update:close="cancel" :size="size" as="form" @submit="submit">
    <div class="">
      <AlertBox />
      <slot />
    </div>
    <template #footer>
      <div class="grid w-full h-full grid-cols-2 gap-3">
        <button type="button" @click="cancel" class="w-full lvp-button cancel">
          {{ cancelLabel }}
        </button>
        <button type="submit" class="w-full lvp-button confirm">
          {{ submitLabel }}
        </button>
      </div>
    </template>
  </LVPModal>
</template>
<script setup lang="ts">
import HeadlessModal from "./HeadlessModal.vue";
import AlertBox from "../Widgets/AlertBox.vue";
import LVPModal from "./LVPModal.vue";
const props = defineProps({
  show: Boolean,

  cancelLabel: {
    type: String,
    default: "Cancel",
  },
  submitLabel: {
    type: String,
    default: "Create",
  }, size: {
    type: String,
    default: "md",
  },
  modalTitle: {
    type: String,
    default: "Create",
  },
});

const emit = defineEmits(["update:show", "submit", "close"]);

const submit = () => {
  emit("submit");
};
const cancel = () => {
  emit("close");
  emit("update:show", false);
};
</script>
