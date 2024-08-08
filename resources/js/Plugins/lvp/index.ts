import { App, Component, Plugin } from "vue";
import "./../../../css/app.scss";
import { router } from "@inertiajs/vue3";
import icons from "./../../helpers/lvp_icons";
import widgets from "./../../Components/Widgets";
import VueApexCharts from "vue3-apexcharts";
import PrimeVue from "primevue/config";
import Aura from "@primevue/themes/aura";
import VueAwesomePaginate from "vue-awesome-paginate";
import LVPToast from "lvp/Plugins/toast";
import { createPinia } from "pinia";
import { createPersistedState } from "pinia-plugin-persistedstate";
import { vMaska } from "maska/vue"
const pinia = createPinia();
pinia.use(createPersistedState());
interface SingleItemActionsOptions {
    item: any;
    route_list: any;
    router: typeof router;
    showToast: (option: ToastOption) => void;
    confirmation_modal: {
        show: boolean;
        title: string;
        body: string;
        cancel_button_label: string;
        confirm_button_label: string;
        onConfirm: (result: boolean) => void;
        onCancel: (result: boolean) => void;
    };
}
interface ToastOption {
    title: string;
    message: string;
    type: "success" | "error" | "warning" | "info";
}
interface SelectedItemsActionOptions {
    selected_items: any;
    route_list: any;
    router: typeof router;
    showToast: (option: ToastOption) => void;
    confirmation_modal: {
        show: boolean;
        title: string;
        body: string;
        cancel_button_label: string;
        confirm_button_label: string;
        onConfirm: (result: boolean) => void;
        onCancel: (result: boolean) => void;
    };
}

interface LVPPluginOptions {
    resources_actions: {
        single_item_actions?: {
            [key: string]: (options: SingleItemActionsOptions) => any;
        };
        selected_items_actions?: {
            [key: string]: (options: SelectedItemsActionOptions) => any;
        };
    };
    actions?: {
        [key: string]: (options: SelectedItemsActionOptions) => any;
    };
    svg_icons?: { [key: string]: string };
    widgets?: { [key: string]: Component };
}

const plugin: Plugin = {
    install(
        app: App,
        options: LVPPluginOptions = {
            resources_actions: {
                single_item_actions: {},
                selected_items_actions: {},
            },
            actions: {},
            svg_icons: {},
            widgets: {},
        }
    ) {
        app.use(PrimeVue, {
            theme: {
                preset: Aura,
                options: {
                    prefix: "p",
                    darkModeSelector: "class",
                    cssLayer: false,
                },
            },
        })
            .use(pinia)
            .use(VueApexCharts)
            .use(VueAwesomePaginate)
            .directive("maska", vMaska).use(LVPToast);

        // app.config.globalProperties.lvp_actions = options.actions;
        app.provide("lvp_resources_actions", options.resources_actions);
        app.provide("lvp_actions", options.actions);
        app.provide("lvp_widgets", { ...widgets, ...options.widgets });
        app.provide("lvp_icons", { ...icons, ...options.svg_icons });
    },
};

export type {
    LVPPluginOptions,
    SingleItemActionsOptions,
    SelectedItemsActionOptions,
};
export default plugin;
