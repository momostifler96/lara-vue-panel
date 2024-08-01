<template>
  <TransitionRoot appear :show="show">
    <TransitionChild
      as="template"
      enter="duration-300 ease-out"
      enter-from="opacity-0"
      enter-to="opacity-100"
      leave="duration-200 ease-in"
      leave-from="opacity-100"
      leave-to="opacity-0"
    >
      <div class="fixed inset-0 bg-black/80 backdrop:blur-lg z-[100]" />
    </TransitionChild>
    <TransitionChild
      as="template"
      enter="duration-600 ease-out"
      enter-from="opacity-0 scale-95"
      enter-to="opacity-100 scale-100"
      leave="duration-600 ease-in"
      leave-from="opacity-100 scale-100"
      leave-to="opacity-0 scale-95"
    >
      <div
        class="fixed left-0 right-0 mx-auto my-auto inset-y-0 z-[999] w-[80vw] bg-black h-[80vh]"
      >
        <div class="relative w-full h-full flex-center">
          <button
            type="button"
            @click="closeCropper"
            class="absolute p-1 text-white transition bg-black rounded-full top-2 right-2 ring-1 ring-transparent hover:ring-white w-7 h-7 flex-center"
          >
            <CloseIcon class="w-4 h-4" />
          </button>
          <ImageCropper :image="image" @onCrop="onCrop" />
        </div>
      </div>
    </TransitionChild>
  </TransitionRoot>
</template>
<script setup lang="ts">
import { TransitionRoot, TransitionChild } from "@headlessui/vue";
import CloseIcon from "@heroicons/vue/24/outline/XMarkIcon";
import ImageCropper from "./ImageCropper.vue";
const props = defineProps({
  show: Boolean,
  image: String,
});

const emit = defineEmits(["update:show", "onCrop"]);

const closeCropper = () => {
  emit("update:show", false);
};

const onCrop = (image: string) => {
  emit("onCrop", image);
  emit("update:show", false);
};
</script>
