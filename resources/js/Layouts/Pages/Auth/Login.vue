<template>
  <div class="flex-col w-full h-screen bg-gray-100 flex-center">
    <form @submit.prevent="submit" class="lvp-login-form">
      <div class="mb-5">
        <h1 class="text-2xl font-bold text-center">
          {{ props.page_titles.title }}
        </h1>
        <p class="text-sm text-center text-gray-400">
          {{ props.page_titles.description }}
        </p>
      </div>
      <TextField class="w-full" required v-model="formData.identifiant" :label="props.labels.identifiant"
        id="identifiant" :errorText="errorIsArray($page.props.errors['identifiant'])" />
      <hr class="h-5" />
      <TextField class="w-full" required type="password" id="password" v-model="formData.password"
        :label="props.labels.password" :errorText="errorIsArray($page.props.errors['password'])" />
      <hr class="h-5" />
      <div class="flex items-center w-full">
        <label for="remeber_me" class="flex items-center gap-2 select-none">
          <input type="checkbox" name="remeber_me" id="remeber_me" class="lvp-checkbox"
            v-model="formData.remember_me" />
          <span>{{ props.labels.remember_me }}</span>
        </label>
      </div>
      <hr class="h-5" />
      <SimpleButton type="submit" class="w-full">{{ props.labels.login }}</SimpleButton>
    </form>
  </div>
</template>
<script setup lang="ts">
import { router, useForm, usePage } from "@inertiajs/vue3";
import TextField from "lvp/Components/Forms/TextField.vue";
import SimpleButton from "lvp/Components/Forms/SimpleButton.vue";
import { ref } from "vue";

const props = usePage().props as unknown as any;

const formData = ref({
  identifiant: "",
  password: "",
  remember_me: false,
});
const errorIsArray = ($errors: string[] | string | null): string | null => {
  return $errors ? (Array.isArray($errors) ? $errors[0] : $errors) : null;
};
const submit = () => {
  router.post(route(props.routes.store), formData.value);
};
</script>
