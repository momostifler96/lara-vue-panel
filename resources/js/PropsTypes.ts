interface EventsListeners {
    change: { [k: string]: any }[];
    clear: { [k: string]: any }[];
    save: { [k: string]: any }[];
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
    delete: string;
    delete_confirmation_body: string;
    index_page_title: string;
    edit_resource: string;
    create_resource: string;
    delete_confirmation_title: string;
    label: string;
    form_titles: {
        [key: string]: {
            title: string;
            submit_and_create: string;
            cancel: string;
            submit: string;
        }
    };
    plural_label: string;
}
interface ResourceFormTitles {
    delete: string;
    cancel: string;
    submit: string;
    submit_and_create: string;
    title: string;
}

interface ResourceFormPageProps {
    action: "create" | "edit";
    page_titles: ResourceFormTitles;
    form_data: { [key: string]: any };
    titles: { [key: string]: any };
    form_component: {
        type: string;
        label: string;
        props: {
            fields: any[];
            type: string;
            label: string;
            formData: { [key: string]: any };
            grid_cols?: number;
            gap?: number;
        };
    };
    routes: {
        submit: string;
        index: string;
        cancel: string;
        delete: string;
    };
    resource_data: { [key: string]: any };
    fields: ResourceFormField[];
}

export type { ResourceFormField, ResourceFormPageProps, ResourceTitles, ResourceRoutes, ResourceFormTitles }
