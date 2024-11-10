import { router } from '@inertiajs/vue3'
type ColumnAlign = "left" | "right" | "center";
interface TableColumn {
    searchable: boolean;
    sortable: boolean;
    field: string;
    align: ColumnAlign;
    label: string;
    type: string;
    visible: boolean;
    data: any;
    file_path?: string;
}
interface FormField {
    props: {
        label: string;
        name: string;
        required: boolean;
        placeholder: string;
    };
    eventsListeners: {
        change: {
            fields: string;
            action: string;
            func: string;
            debounce: number;
        }[];
        save: {
            fields: string;
            action: string;
            func: string;
            debounce: number;
        }[];
        clear: {
            fields: string;
            action: string;
            func: string;
            debounce: number;
        }[];
    };
    type: string;
}

interface ActionMenu {
    type: "inline" | "dropdown";
    label: string;
    icon: string;
    actions: LVPActionMenuOption[];
}
interface LVPActionMenuOption {
    label?: string;
    action: string;
    icon?: string;
    color: string;
}
interface TableData {
    columns: any[];
    filters: [];
    group_action: ActionMenu;
    // action: {
    //     type: "inline" | "dropdown";
    //     actions: {
    //         label: string;
    //         action: string;
    //         icon: string;
    //         color: string;
    //     }[];
    // };
    label: string;
    fixe_last_column: boolean;
    fixe_first_column: boolean;
    type: string;
    api_url: null;
    paginated: null;
    data: {
        items: [];
        pagination: {
            current_page: number;
            total_items: number;
            total: number;
            per_page: number;
            from: number;
            to: number;
            path: string;
        };
    };
}

interface ConfirmationModalOption {
    show: boolean,
    title: string,
    body: string,
    cancel_button_label: string,
    confirm_button_label: string,
    onResponse: (rsp: boolean) => {},
}


namespace WidgetPropsTypes {
    export interface FormEngine {
        fields: {
            type: string;
            props: any;
            name: string;
            eventsListeners: {
                change: {
                    fields: string;
                    action: string;
                    func: string;
                    debounce: number;
                }[];
            };
        }[];
        action?: string;
        method?: 'get' | 'post' | 'put' | 'delete';
        formData: any;
        defaultData?: any;
        grid_cols?: number;
        gap?: number | string;
        cancelBtnLabel?: string;
        submitBtnLabel?: string;
        submit_url?: string;
    }
}
interface SingleItemActionsOptions {
    item: any,
    route_list: any,
    router: typeof router,
    showToast: (option: ToastOption) => any,
    confirmationModal: (option: ConfirmationModalOption) => void,
}
interface ToastOption {
    title: string;
    message: string;
    type: "success" | "error" | "warning" | "info";
}

interface SelectedItemsActionOptions {
    selected_items: any,
    route_list: any,
    router: typeof router,
    showToast: (option: ToastOption) => any,
    confirmationModal: (option: ConfirmationModalOption) => void,

}
interface ActionsList {
    [key: string]: (option: SingleItemActionsOptions) => void
}
interface SelectedActionsList {
    [key: string]: (option: SelectedItemsActionOptions) => void
}
interface FileInfo {
    imagePreview?: string;
    fileName: string;
    fileOriginalName: string;
    fileSize: string;
    fileFormatedSize: string;
    fileType: string;
    fileExtension: string;
    file?: File;
}

interface PageProps {
    props: {
        errors: [];
        auth: any;
        notifications: number;
        loading: boolean;
        user_menu: [];
        nav_menu: [];
        flash: {
            success: string;
            error: string;
            warn: string;
            info: string;
            alert: string;
        }
    }

}
interface FolderInterface {
    id: string;
    name: string;
    created_date: string;
    type: 'directory' | 'file' | 'other';
    items: number;
    file_info: {
        url: string;
        download_name: string;
        uuid: string;
        extension: string;
        mime_type: string;
        size: number;
    };
}


export type { TableColumn, LVPActionMenuOption, TableData, ActionsList, SelectedActionsList, FormField, SelectedItemsActionOptions, SingleItemActionsOptions, ActionMenu, FileInfo, PageProps, FolderInterface, WidgetPropsTypes }
