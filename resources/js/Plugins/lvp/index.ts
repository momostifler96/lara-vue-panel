import { App, Plugin } from "vue";
import "./../../../css/app.scss";
import icons from "../../svg_icons";
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
                    // selected_items: {},
                    bulk: {},
                    item_col: {},
                },
                page: {},
                resource_detail_page: {},
            },
            svg_icons: {},
            widgets: {},
            form_fields: {},
            datatable_columns: {},
            data_grid_cards: {},
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
        app.provide("lvp.actions.datatable.bulk", options.actions?.datatable?.bulk);
        app.provide("lvp.actions.datatable.item_col", options.actions?.datatable?.item_col);
        app.provide("lvp.actions.page", options.actions?.page);
        app.provide("lvp.actions.resource_detail_page", options.actions?.resource_detail_page);
        app.provide("lvp.actions.resource_create_page", options.actions?.resource_create_page);
        app.provide("lvp.actions.resource_edit_page", options.actions?.resource_edit_page);
        app.provide("lvp_actions", options.actions);
        app.provide("lvp_widgets", { ...widgets, ...options.widgets });
        app.provide("lvp_icons", { ...icons, ...options.svg_icons });
        app.provide("lvp_form_fields", options.form_fields);
        app.provide("lvp_datatable_columns", options.datatable_columns);
        app.provide("lvp_data_grid_cards", options.data_grid_cards);
    },
};

export default plugin;
