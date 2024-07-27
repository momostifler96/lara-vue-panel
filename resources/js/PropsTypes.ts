interface EventsListeners {
    change: string[];
    clear: string[];
    save: string[];
}

interface InputRegex {
    change: string[];
    clear: string[];
    save: string[];
}

interface VisibilityOptions {
    create: boolean;
    edit: boolean;
}

interface ImageResponsive {
    sm: string;
    md: string;
    lg: string;
}

interface ResourceFormField {
    component_path: string;
    field: string;
    component: string;
    props: { [k: string]: any };
    colspan: string;
    eventsListeners: EventsListeners;
    rules: string[];
    hiddenOn: { [k: string]: boolean };
}

interface ResourceRoutes {
    create: string;
    edit: string;
    update: string;
    index: string;
    store: string;
    delete: string;
    'it-update': string;
}

interface ResourceTitles {
    delete_button: string;
    delete_confirmation_body: string;
    index_page_title: string;
    edit_resource: string;
    create_resource: string;
    delete_confirmation_title: string;
    label: string;
    form_titles: {
        [key: string]: {
            title: string;
            submit_button_and_create: string;
            cancel_button: string;
            submit_button: string;
        }
    };
    plural_label: string;
}

interface ResourceFormPageProps {
    action: "create" | "edit";
    titles: ResourceTitles;
    form_data: { [key: string]: any };
    resources_routes: ResourceRoutes;
    resource_data: { [key: string]: any };
    fields: ResourceFormField[];
}

export type { ResourceFormField, ResourceFormPageProps, ResourceTitles, ResourceRoutes }
