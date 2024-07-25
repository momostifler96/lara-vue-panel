<template>
    <PanelLayout :page-title="props.titles.form_titles[props.action].title">
        <template #actions>
            <SimpleButton
                @click="askDeleteConfromation"
                color="danger"
                v-if="props.action == 'edit'"
                >{{ props.titles.delete_button }}</SimpleButton
            >
        </template>

        <form class="" @submit.prevent="submit" ref="formRef">
            <div class="grid grid-cols-2 gap-4 mb-10">
                <template v-for="(field, i) in props.fields">
                    <TextField
                        v-if="
                            !field.hidden_on[props.action] &&
                            field.type === 'text'
                        "
                        :class="[
                            `col-span-${field.colspan}`,
                            {
                                'col-span-full': field.colspan == 'full',
                            },
                        ]"
                        v-model="_formData[field.field]"
                        :label="field.label"
                        :placeholder="field.placeholder"
                        :readonly="field.readonly_on[props.action]"
                        :disabled="field.disabled_on[props.action]"
                        :errorText="
                            errorIsArray($page.props.errors, field.field)
                        "
                        :required="field.rules.includes('required')"
                    />
                    <DatePicker
                        v-else-if="
                            !field.hidden_on[props.action] &&
                            field.type === 'date'
                        "
                        :class="[
                            `col-span-${field.colspan}`,
                            {
                                'col-span-full': field.colspan == 'full',
                            },
                        ]"
                        v-model="_formData[field.field]"
                        :label="field.label"
                        :placeholder="field.placeholder"
                        :readonly="field.readonly_on[props.action]"
                        :disabled="field.disabled_on[props.action]"
                        :type="field.date_type"
                        :minDate="field.min_date"
                        :maxDate="field.max_date"
                        :range="field.is_range"
                        :errorText="
                            errorIsArray($page.props.errors, field.field)
                        "
                        :required="field.rules.includes('required')"
                    />
                    <TextAreaField
                        v-else-if="
                            !field.hidden_on[props.action] &&
                            field.type === 'textarea'
                        "
                        :class="[
                            `col-span-${field.colspan}`,
                            {
                                'col-span-full': field.colspan == 'full',
                            },
                        ]"
                        v-model="_formData[field.field]"
                        :label="field.label"
                        :placeholder="field.placeholder"
                        :readonly="field.readonly_on[props.action]"
                        :disabled="field.disabled_on[props.action]"
                        :errorText="
                            errorIsArray($page.props.errors, field.field)
                        "
                        :required="field.rules.includes('required')"
                    />
                    <FormSelectField
                        v-else-if="
                            !field.hidden_on[props.action] &&
                            field.type === 'select'
                        "
                        :class="[
                            `col-span-${field.colspan}`,
                            {
                                'col-span-full': field.colspan == 'full',
                            },
                        ]"
                        v-model="_formData[field.field]"
                        :label="field.label"
                        :placeholder="field.label"
                        :filter="field.filter"
                        :required="field.rules.includes('required')"
                        :readonly="field.readonly_on[props.action]"
                        :disabled="field.disabled_on[props.action]"
                        :errorText="
                            errorIsArray($page.props.errors, field.field)
                        "
                        :options="field.options"
                        :ajaxCall="field.ajax_call"
                        :multiple="field.multiple"
                    />

                    <FileUploader
                        v-else-if="
                            !field.hidden_on[props.action] &&
                            field.type === 'file'
                        "
                        :class="[
                            `col-span-${field.colspan}`,
                            {
                                'col-span-full': field.colspan == 'full',
                            },
                        ]"
                        v-model="_formData[field.field]"
                        :label="field.label"
                        :placeholder="field.label"
                        :required="field.rules.includes('required')"
                        :readonly="field.readonly_on[props.action]"
                        :disabled="field.disabled_on[props.action]"
                        :field="field.field"
                        :options="field.options"
                    />
                </template>
            </div>
            <div class="flex justify-between">
                <div class="flex gap-2">
                    <SimpleButton type="submit" @click="submitForm('leave')">{{
                        props.titles.form_titles[props.action].submit_button
                    }}</SimpleButton>
                    <SimpleButton
                        v-if="props.action == 'create'"
                        type="submit"
                        @click="submitForm('reload')"
                    >
                        {{
                            props.titles.form_titles[props.action]
                                .submit_button_and_create
                        }}</SimpleButton
                    >
                </div>
                <div class="">
                    <SimpleButton
                        button-type="link"
                        :href="route(props.resources_routes.index)"
                        color="danger"
                        class="flex items-center gap-2"
                    >
                        {{
                            props.titles.form_titles[props.action].cancel_button
                        }}
                    </SimpleButton>
                </div>
            </div>
        </form>
        <ConfirmationModal
            :show="confirmation_modal.show"
            icon="delete"
            :title="confirmation_modal.title"
            :body="confirmation_modal.body"
            @onResponse="confirmation_modal.onConfirm"
        />
    </PanelLayout>
</template>
<script setup lang="ts">
import PanelLayout from "lvp/Layouts/Partials/PanelLayout.vue";
import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import FormSelectField from "lvp/Components/Forms/FormSelectField.vue";
import TextField from "lvp/Components/Forms/TextField.vue";
import Select from "lvp/Components/Forms/Select.vue";
import TextAreaField from "lvp/Components/Forms/TextAreaField.vue";
import SimpleButton from "lvp/Components/Buttons/SimpleButton.vue";
import FileUploader from "lvp/Components/Forms/FileUploader.vue";
import { reactive, ref } from "vue";
import ConfirmationModal from "lvp/Components/Dialogs/ConfirmationModal.vue";
import type {
    ResourceFormField,
    ResourceFormPageProps,
} from "../../PropsTypes";
import DatePicker from "lvp/Components/Forms/DatePicker.vue";

const props = usePage().props as unknown as ResourceFormPageProps;

const formRef = ref(null);

const errorIsArray = (errors: any, field: string): string | null => {
    const error = errors[field];
    return error ? (Array.isArray(error) ? error[0] : error) : null;
};
const submitForm = (type: "reload" | "leave") => {
    _formData.after_save = type;
    // formRef.value.submit();
};

const _formData = reactive<{ [k: string]: any }>({
    ...props.form_data,
    after_save: "leave",
});
const submit = () => {
    router.post(
        route(
            props.resources_routes[props.action == "edit" ? "update" : "store"]
        ),
        _formData
    );
};
const confirmation_modal = reactive({
    show: false,
    title: props.titles.delete_confirmation_title,
    body: props.titles.delete_confirmation_body,
    current_id: "",
    onConfirm: (rsp: boolean) => {
        if (rsp) {
            router.delete(
                route(props.resources_routes.delete, {
                    id: props.form_data.id,
                })
            );
        }
        confirmation_modal.show = false;
    },
    onCancel: () => {
        confirmation_modal.show = false;
        confirmation_modal.title = "";
        confirmation_modal.body = "";
        confirmation_modal.current_id = "";
    },
});

const askDeleteConfromation = () => {
    confirmation_modal.show = true;
};
</script>
