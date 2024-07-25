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
    label: string;
    type: string;
    events_listeners: EventsListeners;
    input_regex: InputRegex;
    autofocus: boolean;
    placeholder: string;
    ajax_call: string | null;
    icon: string | null;
    iconPosition: string | null;
    showLabel: boolean;
    helpText: string | null;
    showHelpText: string | null;
    showError: boolean;
    hidden_on: VisibilityOptions;
    readonly_on: VisibilityOptions;
    disabled_on: VisibilityOptions;
    required_on: VisibilityOptions;
    filter: boolean;
    colspan: string;
    rules: string[];
    file_type: string;
    max_file_size: string;
    image_ratio: string;
    image_responsive: ImageResponsive;
    options: any; // This can be further defined based on the expected structure of options
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
