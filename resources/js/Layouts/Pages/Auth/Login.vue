<template>
    <div class="flex-col w-full h-screen bg-gray-100 flex-center">
        <form @submit.prevent="submit" class="lvp-login-form">
            <div class="mb-5">
                <h1 class="text-2xl font-bold text-center">
                    {{ props.form_title }}
                </h1>
                <p class="text-sm text-center text-gray-400">
                    {{ props.form_sub_title }}
                </p>
            </div>
            <TextField
                class="w-full"
                required
                v-model="formData.identifiant"
                label="Identifiant"
                id="identifiant"
                :errorText="errorIsArray($page.props.errors['identifiant'])"
            />
            <hr class="h-5" />
            <TextField
                class="w-full"
                required
                type="password"
                id="password"
                v-model="formData.password"
                label="Password"
                :errorText="errorIsArray($page.props.errors['password'])"
            />
            <hr class="h-5" />
            <div class="flex items-center w-full">
                <label
                    for="remeber_me"
                    class="flex items-center gap-2 select-none"
                >
                    <input
                        type="checkbox"
                        name="remeber_me"
                        id="remeber_me"
                        class="lvp-checkbox"
                        v-model="formData.remember_me"
                    />
                    <span>Remember me</span>
                </label>
            </div>
            <hr class="h-5" />
            <SimpleButton type="submit" class="w-full">Submit</SimpleButton>
        </form>
    </div>
</template>
<script setup lang="ts">
import { useForm, usePage } from "@inertiajs/vue3";
import TextField from "lvp/Components/Forms/TextField.vue";
import SimpleButton from "lvp/Components/Forms/SimpleButton.vue";

const props = usePage().props as unknown as any;

// defineProps({
//     errors: Object,
//     form_title: String,
//     page_title: String,
//     meta_title: String,
//     custom_data: Object,
//     submit_button_label: Object,
//     fields: Object,
//     form_sub_title: Object,
//     page_routes: {
//         type: Object,
//         default: { index: "", store: "" },
//     },
// });
const formData = useForm({
    identifiant: "",
    password: "",
    remember_me: false,
});
const errorIsArray = ($errors: string[] | string | null): string | null => {
    return $errors ? (Array.isArray($errors) ? $errors[0] : $errors) : null;
};
const submit = () => {
    formData.post(route(props.page_routes.store));
};
</script>
