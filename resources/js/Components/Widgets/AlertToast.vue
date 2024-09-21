<template>
    <LvpToast />
    <!-- <Toast /> -->
    <template v-if="$page.props.flash.success">
        <span>{{ showSuccess($page.props.flash.success) }}</span>
    </template>
    <template v-else-if="$page.props.flash.error">
        <span>{{ showError($page.props.flash.error) }}</span>
    </template>
    <template v-else-if="$page.props.flash.warning">
        <span>{{ showWarning($page.props.flash.warning) }}</span>
    </template>
    <template v-else-if="$page.props.flash.info">
        <span>{{ showInfo($page.props.flash.info) }}</span>
    </template>
</template>
<script setup lang="ts">
import { useToast } from "lvp/Plugins/toast";

const showSuccess = ($message: string) => {
    useToast({
        type: "success",
        message: $message,
        title: "Success",
    });
};
const showError = ($message: string) => {
    useToast({
        type: "error",
        message: $message,
        title: "Erreur",
    });
};
const showWarning = ($message: string) => {
    useToast({
        type: "warning",
        message: $message,
        title: "Attention",
    });
};
const showInfo = ($message: string) => {
    useToast({
        type: "info",
        message: $message,
        title: "Infos",
    });
};
</script>
