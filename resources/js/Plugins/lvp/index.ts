import { App, Plugin } from "vue";
import "./../../../css/app.scss";
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
import { LVPPluginOptions } from "lvp/Types";
const pinia = createPinia();
pinia.use(createPersistedState());

const plugin: Plugin = {
    install(
        app: App,
        options: LVPPluginOptions = {
            actions: {
                datatable: {
                    item: {},
                    selected_items: {},
                },
                page: {},
                resource_detail_page: {},
            },
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

        app.provide("lvp.actions.datatable.item", options.actions?.datatable?.item);
        app.provide("lvp.actions.datatable.selected_items", options.actions?.datatable?.selected_items);
        app.provide("lvp.actions.page", options.actions?.page);
        app.provide("lvp.actions.resource_detail_page", options.actions?.resource_detail_page);
        app.provide("lvp.actions.resource_create_page", options.actions?.resource_create_page);
        app.provide("lvp.actions.resource_edit_page", options.actions?.resource_edit_page);
        app.provide("lvp_actions", options.actions);
        app.provide("lvp_widgets", { ...widgets, ...options.widgets });
        app.provide("lvp_icons", { ...icons, ...options.svg_icons });
    },
};

export default plugin;
