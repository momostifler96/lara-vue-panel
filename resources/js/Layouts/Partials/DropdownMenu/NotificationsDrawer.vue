<template>
    <Drawer position="left" v-model:show="showNotifications">
        <div class="flex items-center justify-between p-3 border-b">
            <h3 class="font-bold">Notifications</h3>
            <button class="lvp-icon-button primary" @click="loadNotifications">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6 text-lvp-primary"
                    :class="{ 'spinner-loading': loading }"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                    />
                </svg>
            </button>
        </div>
        <div class="p-3">
            <div class="p-5 text-center" v-if="notifications.length == 0">
                Empty
            </div>
            <template
                v-else
                v-for="(notification, i) in notifications"
                :key="notification.date"
            >
                <span class="notification">
                    <span
                        class="w-8 h-8"
                        v-html="svg_icons[notification.status]"
                    >
                    </span>
                    <div class="w-full">
                        <h6>{{ notification.title }}</h6>
                        <p>{{ notification.body }}</p>
                        <p>{{ notification.date }}</p>
                    </div>
                    <span class="flex-center">
                        <button
                            class="w-6 h-6 flex-center"
                            @click="readNotification(notification.id, i)"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-6 h-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </span>
                </span>
            </template>
        </div>
    </Drawer>
</template>
<script setup lang="ts">
import { TransitionRoot, TransitionChild } from "@headlessui/vue";
import Drawer from "../Drawer.vue";
import { onMounted, ref } from "vue";
import HTTP from "lvp/helpers/http";
const showNotifications = ref(false);

interface Notification {
    title: string;
    body: string;
    date: string;
    status: string;
    id: string;
}

const notifications = ref<Notification[]>([]);
const loading = ref(false);
const svg_icons = <{ [ket: string]: string }>{
    success: `
    <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="9.5" cy="9.5" r="8.5" fill="#0AFF5E" fill-opacity="0.2"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.90702 9.12185C6.47345 8.81097 5.86995 8.91042 5.55906 9.34399V9.34399C5.24818 9.77756 5.34763 10.3811 5.7812 10.6919L8.91062 12.9359C9.35945 13.2577 9.98419 13.1547 10.306 12.7059L14.3054 7.12827C14.5728 6.75529 14.4873 6.23612 14.1143 5.96868V5.96868C13.7413 5.70124 13.2222 5.7868 12.9547 6.15978L10.0812 10.1673C9.75933 10.6162 9.13459 10.7191 8.68576 10.3973L6.90702 9.12185Z" fill="#0AFF5E"/>
    </svg>
    `,
    error: `
    <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="9.5" cy="9.5" r="8.5" fill="#FF2063" fill-opacity="0.2"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.4093 12.5C11.7105 12.8012 12.199 12.8012 12.5002 12.5C12.8015 12.1987 12.8015 11.7103 12.5002 11.4091L10.591 9.49989L12.5001 7.59082C12.8013 7.28957 12.8013 6.80114 12.5001 6.49989C12.1988 6.19864 11.7104 6.19864 11.4092 6.49989L9.5001 8.40896L7.59103 6.4999C7.28978 6.19864 6.80136 6.19864 6.5001 6.4999C6.19885 6.80115 6.19885 7.28957 6.50011 7.59082L8.40917 9.49989L6.49999 11.4091C6.19874 11.7103 6.19874 12.1987 6.49999 12.5C6.80124 12.8012 7.28967 12.8012 7.59092 12.5L9.5001 10.5908L11.4093 12.5Z" fill="#CE2525"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.4093 12.5C11.7105 12.8012 12.199 12.8012 12.5002 12.5C12.8015 12.1987 12.8015 11.7103 12.5002 11.4091L10.591 9.49989L12.5001 7.59082C12.8013 7.28957 12.8013 6.80114 12.5001 6.49989C12.1988 6.19864 11.7104 6.19864 11.4092 6.49989L9.5001 8.40896L7.59103 6.4999C7.28978 6.19864 6.80136 6.19864 6.5001 6.4999C6.19885 6.80115 6.19885 7.28957 6.50011 7.59082L8.40917 9.49989L6.49999 11.4091C6.19874 11.7103 6.19874 12.1987 6.49999 12.5C6.80124 12.8012 7.28967 12.8012 7.59092 12.5L9.5001 10.5908L11.4093 12.5Z" fill="#FF2063"/>
    </svg>
    `,
    warning: `
    <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="9.5" cy="9.5" r="8.5" fill="#DEFF5C" fill-opacity="0.2"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.50005 6C8.83731 6 8.30005 6.53726 8.30005 7.2V10.4C8.30005 11.0627 8.83731 11.6 9.50005 11.6C10.1628 11.6 10.7 11.0627 10.7 10.4V7.2C10.7 6.53726 10.1628 6 9.50005 6ZM9.10005 12.4C8.65822 12.4 8.30005 12.7582 8.30005 13.2C8.30005 13.6418 8.65822 14 9.10005 14H9.90005C10.3419 14 10.7 13.6418 10.7 13.2C10.7 12.7582 10.3419 12.4 9.90005 12.4H9.10005Z" fill="#D0CB5C"/>
    </svg>
    `,
    info: `
    <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="9.5" cy="9.5" r="8.5" fill="#5C76FF" fill-opacity="0.2"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.50005 6C8.83731 6 8.30005 6.53726 8.30005 7.2V10.4C8.30005 11.0627 8.83731 11.6 9.50005 11.6C10.1628 11.6 10.7 11.0627 10.7 10.4V7.2C10.7 6.53726 10.1628 6 9.50005 6ZM9.10005 12.4C8.65822 12.4 8.30005 12.7582 8.30005 13.2C8.30005 13.6418 8.65822 14 9.10005 14H9.90005C10.3419 14 10.7 13.6418 10.7 13.2C10.7 12.7582 10.3419 12.4 9.90005 12.4H9.10005Z" fill="#5C76FF"/>
    </svg>
    `,
};
const loadNotifications = async () => {
    loading.value = true;
    const { data } = await HTTP.get(route("admin.rest.notifications"));
    console.log("loadNotifications", data);
    notifications.value = data;
    loading.value = false;
};
const readNotification = async (id: string, ind: number) => {
    notifications.value.splice(ind, 1);
    const { data } = await HTTP.post(route("admin.rest.read-notifications"), {
        id,
    });
    console.log("readNotification", data);
};

onMounted(() => {
    document.addEventListener("open-notification", (e) => {
        showNotifications.value = true;
        if (notifications.value.length == 0) {
            loadNotifications();
        }
    });
});
</script>
<style>
.notification {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    margin: 1rem 0rem;
    padding: 0.5rem;
    background: white;
    box-shadow: 0px 1px 20px 0px #c0c0c05d;
    svg {
        color: #0aff5e;
    }
    h6 {
        font-weight: 600;
        font-size: 14px;
    }
    p {
        font-size: 12px;
    }
    &.success {
        svg {
            color: #0aff5e;
        }
    }
    &.error {
        svg {
            color: #ff3860;
        }
    }
    &.warning {
        svg {
            color: #ffdd57;
        }
    }
    &.info {
        svg {
            color: #209cee;
        }
    }
}
.spinner-icon {
    animation: rotate 1s infinite ease;
}
.spinner-loading {
    animation: rotate 1s infinite ease;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
