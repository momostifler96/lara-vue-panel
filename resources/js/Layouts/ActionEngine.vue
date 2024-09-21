<template>
    <component v-for="(action) in actions" :is="actions_list[action.action_type]" v-bind="action" />
</template>
<script setup lang="ts">
import ActionButton from 'lvp/Components/Actions/ActionButton.vue';
import ActionDropdown from 'lvp/Components/Actions/ActionDropdown.vue';
import ActionForm from 'lvp/Components/Actions/ActionForm.vue';
import ActionLink from 'lvp/Components/Actions/ActionLink.vue';


const props = defineProps({
    actions: {
        type: Array as () => any,
        required: true,
    },
})

const actions_list = <{ [k: string]: any }>{
    form: ActionForm,
    button: ActionButton,
    link: ActionLink,
    dropDown: ActionDropdown,

}
</script>