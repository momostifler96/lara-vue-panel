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
import FormEngine from '../Widgets/FormEngine.vue';
import { router } from "@inertiajs/vue3";
import LVPModal from '../Dialogs/LVPModal.vue';
import type { WidgetPropsTypes } from "lvp/helpers/types";

interface Props {
    form: {
        props: WidgetPropsTypes.FormEngine
    };
    label: string;
    btn_class: string;
    icon_position: string;
    icon: string;
}
const props = defineProps<Props>()

const showModal = ref(false);

const formData = ref({
    action: props.form.props.action,
});


const submit = () => {
    if (!props.form.props.method || !props.form.props.submit_url) return;
    router[props.form.props.method](props.form.props.submit_url, formData.value, {
        onSuccess: () => {
            showModal.value = false;
            formData.value = { action: props.form.props.action };
        },
    });

};
</script>