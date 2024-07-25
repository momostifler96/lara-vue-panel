<template>
    <PanelLayout :pageTitle="props.title">
        <template #actions>
            <component
                v-for="(action, i) in props.header_actions"
                :is="action[action.action_type]"
                v-bind="action"
                :key="`widget-${action.action_type}-${i}`"
            />
        </template>
        <WidgetLayout :widgets="props.widgets" />
    </PanelLayout>
</template>
<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed, inject } from "vue";
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import StateStyleA from "lvp/Components/Widgets/States/StateStyleA.vue";
import Section from "lvp/Components/Widgets/Section.vue";
import PanelLayout from "./Partials/PanelLayout.vue";
import WidgetLayout from "./WidgetLayout.vue";

const props = computed(() => {
    return usePage().props as unknown as any;
});

const actions = {
    button: SimpleButton,
};
</script>
