<template>
  <SimpleButton color="danger" class="flex items-center gap-2">
    {{ label }}
  </SimpleButton>
  <Teleport to="body">
    <ConfirmationModal
      :show="confirmation_modal.show"
      icon="delete"
      :title="confirmationTitle"
      :body="confirmationMessage"
      @onResponse="confirmation_modal.onConfirm"
    />
  </Teleport>
</template>
<script setup lang="ts">
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";

const props = defineProps({
  confirmationTitle: {
    type: String,
    default: "Supprimer",
  },
  confirmationMessage: {
    type: String,
    default: "messag",
  },
  confirmationConfirmBtnLabel: {
    type: String,
    default: "messag",
  },
  confirmationCancelBtnLabel: {
    type: String,
    default: "",
  },
  label: {
    type: Object,
    default: null,
  },
  hasConfirmation: {
    type: Boolean,
    default: true,
  },
});
const emit = defineEmits(["delete"]);
const confirmation_modal = reactive({
  show: false,
  title: props.titles.delete_confirmation_title,
  body: props.titles.delete_confirmation_body,
  onConfirm: (rsp: boolean) => {
    if (rsp) {
      emit("delete");
    }
    confirmation_modal.show = false;
  },
  onCancel: () => {
    confirmation_modal.show = false;
    confirmation_modal.title = "";
    confirmation_modal.body = "";
  },
});
</script>
