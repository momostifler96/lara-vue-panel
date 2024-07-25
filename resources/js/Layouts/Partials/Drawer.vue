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
            <div
                class="fixed inset-0 bg-black/50 z-[100]"
                @click="() => emit('update:show', false)"
            />
        </TransitionChild>
        <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            :enter-from="`opacity-0 ${
                position === 'right'
                    ? 'translate-x-[100%]'
                    : 'translate-x-[-100%]'
            }`"
            enter-to="opacity-100 translate-x-0"
            leave="duration-200 ease-in"
            leave-from="opacity-100 translate-x-0"
            :leave-to="`opacity-0 ${
                position === 'right'
                    ? 'translate-x-[100%]'
                    : 'translate-x-[-100%]'
            }`"
        >
            <div
                :class="`fixed inset-y-0 z-[999] w-96 bg-white h-screen ${
                    position === 'right' ? 'right-0' : 'left-0'
                }`"
            >
                <div class="relative w-full h-full">
                    <slot />
                </div>
            </div>
        </TransitionChild>
    </TransitionRoot>
</template>

<script setup lang="ts">
import { TransitionRoot, TransitionChild } from "@headlessui/vue";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    show: Boolean,
    position: {
        type: String as () => "left" | "right",
        default: "right",
    },
});

const emit = defineEmits(["update:show"]);
</script>
