<template>
    <button :class="btn_class" @click="showModal = true">{{ props.label }}</button>

    <LVPModal :show="showModal" @close="showModal = false" size="md" :title="label" as="form" @submit="submit">
        <FormEngine v-bind="form.props" :formData="formData" />

        <template #footer>
            <div class="grid w-full h-full grid-cols-2 gap-3">
                <button type="button" @click="showModal = false" class="lvp-button cancel">
                    {{ form.props.cancelBtnLabel }}
                </button>
                <button type="submit" class="lvp-button confirm">
                    {{ form.props.submitBtnLabel }}
                </button>
            </div>
        </template>
    </LVPModal>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import FormModal from '../Dialogs/FormModal.vue';
import { CloseIcon } from 'lvp/svg_icons';
import { TransitionRoot, TransitionChild } from "@headlessui/vue";
import FormEngine from '../Widgets/FormEngine.vue';
import { router } from "@inertiajs/vue3";
import LVPModal from '../Dialogs/LVPModal.vue';


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
const formData = ref({
    action: props.form.props.action,
});


const form_grids = <{ [k: string]: any }>{

}

const submit = () => {
    router[props.form.props.method](props.form.props.submit_url, formData.value, {
        onSuccess: () => {
            showModal.value = false;
            formData.value = { action: props.form.props.action };
        },
    });

};
</script>