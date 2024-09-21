<template>
    <button :class="btn_class" @click="showModal = true">{{ props.label }}</button>
    <Teleport to="body">
        <TransitionRoot appear :show="showModal">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/80 backdrop:blur-lg z-[100]" @click="showModal = false" />
            </TransitionChild>
            <TransitionChild as="template" enter="duration-600 ease-out" enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100" leave="duration-600 ease-in" leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95">
                <form class="lvp-modal" @submit.prevent="submit">
                    <div class="modal-header">
                        <button type="button" @click="showModal = false" class="lvp-modal-close-btn">
                            <span v-html="CloseIcon" class="w-5 h-5" />
                        </button>
                        <h6 class="font-bold">{{ label }}</h6>
                    </div>
                    <div class="modal-content">
                        <FormEngine v-bind="form.props" :formData="formData" />
                    </div>
                    <div class="modal-footer grid w-full h-full grid-cols-2 gap-3">
                        <button type="button" @click="showModal = false" class="lvp-button cancel">
                            {{ form.props.cancelBtnLabel }}
                        </button>
                        <button type="submit" class="lvp-button confirm">
                            {{ form.props.submitBtnLabel }}
                        </button>
                    </div>
                </form>
            </TransitionChild>
        </TransitionRoot>
    </Teleport>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import FormModal from '../Dialogs/FormModal.vue';
import { CloseIcon } from 'lvp/helpers/lvp_icons';
import { TransitionRoot, TransitionChild } from "@headlessui/vue";
import FormEngine from '../Widgets/FormEngine.vue';
import { router } from "@inertiajs/vue3";


const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    btn_class: {
        type: String,
        required: true,
    },
    icon_position: {
        type: String as () => "left" | "right",
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
})

const showModal = ref(false);

console.log('props', props);
const formData = ref({});


const form_grids = <{ [k: string]: any }>{

}

const submit = () => {
    router[props.form.props.method](props.form.props.action, formData.value, {
        onSuccess: () => {
            showModal.value = false;
            formData.value = {};
        },
    });

};
</script>