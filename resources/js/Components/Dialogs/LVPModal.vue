<template>
  <TransitionRoot appear :show="show">
    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
      leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
      <div class="fixed inset-0 bg-black/80 backdrop:blur-lg z-[100]" @click="close" />
    </TransitionChild>
    <TransitionChild as="template" enter="duration-600 ease-out" enter-from="opacity-0 scale-95"
      enter-to="opacity-100 scale-100" leave="duration-600 ease-in" leave-from="opacity-100 scale-100"
      leave-to="opacity-0 scale-95">
      <component :is="as" class="lvp-modal" :class="sizes[size]" @submit.prevent="$emit('submit')">
        <div class="modal-header">
          <button type="button" @click="close" class="lvp-modal-close-btn">
            <span v-html="CloseIcon" class="w-5 h-5" />
          </button>
          <h6 class="font-bold text-xl">{{ title }}</h6>
        </div>
        <div class="max-h-[80vh] h-fit overflow-y-auto">
          <slot />
        </div>
        <div class="modal-footer pt-3" v-if="$slots.footer">
          <slot name="footer" />
        </div>
      </component>
    </TransitionChild>
  </TransitionRoot>

</template>
<script setup lang="ts">
import { TransitionRoot, TransitionChild } from "@headlessui/vue";
import { CloseIcon } from "lvp/svg_icons";
const props = defineProps({
  show: Boolean,
  title: {
    type: String,
    default: "",
  },
  size: {
    type: String,
    default: 'md'
  }, as: {
    type: String,
    default: 'div'
  }
});

const emit = defineEmits(["close", "update:close", 'submit']);
const close = () => {
  emit("update:close");
};

const sizes = <{ [k: string]: string }>{
  xs: 'w-[calc(100vw-50px)] lg:w-[400px]',
  sm: 'w-[calc(100vw-50px)] md:w-[700px] lg:w-[700px]',
  md: 'w-[calc(100vw-100px)] md:w-[calc(100vw-300px)] lg:w-[calc(100vw-600px)] xl:w-[calc(100vw-800px)]',
  lg: 'w-[calc(100vw-100px)] md:w-[calc(100vw-150px)] lg:w-[calc(100vw-400px)] xl:w-[calc(100vw-600px)]',
  xl: 'w-[calc(100vw-100px)] md:w-[calc(100vw-150px)] lg:w-[calc(100vw-150px)] xl:w-[calc(100vw-200px)]',
  '2xl': 'w-[calc(100vw-100px)]',
}
</script>
